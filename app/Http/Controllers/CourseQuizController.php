<?php

namespace App\Http\Controllers;

use App\Models\Course_Quiz;
use App\Models\Course_Content;
use App\Models\Course_Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseQuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Student')) {
            $enrolledCourseIds = $user->courseStudents()->pluck('course_id');

            $courseStudent = Course_Student::where('user_id', $user->id)->first();
            $currentPercentage = $courseStudent->percentage ?? 0;

            $courseQuizzes = Course_Quiz::whereHas('courseContent', function ($query) use ($enrolledCourseIds) {
                $query->whereIn('course_id', $enrolledCourseIds);
            })->with('courseContent')->get();

            $fixedTotalQuizzes = 8;
            $incrementPerQuiz = 100 / $fixedTotalQuizzes;

            $quizzesCompletedCount = ($incrementPerQuiz > 0) ? floor($currentPercentage / $incrementPerQuiz) : 0;

            foreach ($courseQuizzes as $index => $quiz) {
                $quiz->is_submitted = ($index < $quizzesCompletedCount);
            }

        } elseif ($user->hasRole('Tutor')) {
        $tutorCourseIds = $user->courses()->pluck('courses.id');

        $courseQuizzes = Course_Quiz::whereHas('courseContent', function ($query) use ($tutorCourseIds) {
                $query->whereIn('course_id', $tutorCourseIds);
            })
            ->with('courseContent.course')
            ->latest()
            ->paginate(10);

        } else {
            $courseQuizzes = Course_Quiz::with('courseContent')->paginate(10);
        }

        return view('course-quiz.index', compact('courseQuizzes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courseContents = Course_Content::all();
        return view('course-quiz.create', compact('courseContents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'course_content_id' => 'required|exists:course_contents,id',
            'name' => 'required|string|max:255',
        ]);

        $courseQuiz = Course_Quiz::create($validatedData);

           return redirect()->route('course-quiz.show', $courseQuiz->id)
                     ->with('success', 'Quiz berhasil dibuat! Silakan tambahkan pertanyaan.');
    }

    /**
     * Display the specified resource (Halaman Kelola Soal untuk Tutor & Kerjakan Quiz untuk Student).
     */
    public function show(Course_Quiz $courseQuiz)
    {
        $courseQuiz->load('courseContent');
        return view('course-quiz.show', compact('courseQuiz'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course_Quiz $courseQuiz)
    {
        $courseContents = Course_Content::all();
        return view('course-quiz.edit', compact('courseQuiz', 'courseContents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course_Quiz $courseQuiz)
    {
        $validatedData = $request->validate([
            'course_content_id' => 'required|exists:course_contents,id',
            'name' => 'required|string|max:255',
            'questions' => 'nullable|array',
        ]);

        $courseQuiz->update($validatedData);

        return redirect()->route('course-quiz.index')->with('success', 'Course quiz updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course_Quiz $courseQuiz)
    {
        $courseQuiz->delete();

        return redirect()->route('course-quiz.index')->with('success', 'Course quiz deleted successfully.');
    }

    /**
     * Menyimpan soal baru ke dalam kolom JSON (Untuk Tutor).
     */
    public function storeQuestion(Request $request, Course_Quiz $courseQuiz)
    {
        $request->validate([
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:A,B,C,D',
        ]);

        // Ambil soal yang sudah ada atau buat array kosong jika belum ada
        $questions = $courseQuiz->questions ?? [];

        // Buat struktur soal baru dengan unik ID
        $newQuestion = [
            'id' => uniqid(),
            'question' => $request->question,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'correct_answer' => $request->correct_answer,
        ];

        // Masukkan ke array
        $questions[] = $newQuestion;

        // Simpan kembali ke kolom JSON
        $courseQuiz->update([
            'questions' => $questions
        ]);

        return back()->with('success', 'Soal berhasil ditambahkan!');
    }

    /**
     * Menghapus soal berdasarkan ID unik di dalam JSON (Untuk Tutor).
     */
    public function destroyQuestion(Course_Quiz $courseQuiz, $questionId)
    {
        $questions = $courseQuiz->questions ?? [];

        // Filter array untuk membuang soal yang memiliki id yang sesuai
        $filteredQuestions = array_values(array_filter($questions, function ($q) use ($questionId) {
            return $q['id'] !== $questionId;
        }));

        $courseQuiz->update([
            'questions' => $filteredQuestions
        ]);

        return back()->with('success', 'Soal berhasil dihapus!');
    }

    /**
     * Menangani proses saat Student mengirimkan jawaban quiz.
     */
    public function submitQuiz(Request $request, $quizId)
    {
        $quiz = Course_Quiz::with('courseContent')->findOrFail($quizId);
        $user = auth()->user();

        $studentAnswers = $request->input('answers', []);
        $questions = $quiz->questions ?? [];
        $totalQuestions = count($questions);
        $correctCount = 0;

        foreach ($questions as $question) {
            $qId = $question['id'];
            if (isset($studentAnswers[$qId]) && $studentAnswers[$qId] === $question['correct_answer']) {
                $correctCount++;
            }
        }

        $score = $totalQuestions > 0 ? ($correctCount / $totalQuestions) * 100 : 0;
        $courseId = $quiz->courseContent->course_id ?? null;

        if ($courseId) {
            $courseStudent = Course_Student::where('user_id', $user->id)
                                ->where('course_id', $courseId)
                                ->first();

            if ($courseStudent) {
                $fixedTotalQuizzes = 8;
                $incrementPerQuiz = 100 / $fixedTotalQuizzes;
                
                $currentPercentage = $courseStudent->percentage ?? 0;
                
                $newPercentage = min(100, $currentPercentage + $incrementPerQuiz);
                $isComplete = ($newPercentage >= 100);

                $courseStudent->update([
                    'percentage' => round($newPercentage, 2),
                    'is_complete' => $isComplete,
                    'completed_at' => $isComplete ? now() : null,
                ]);
            }
        }

        return redirect()->route('course-quiz.index')->with('success', "Quiz berhasil dikumpulkan! Skor: " . number_format($score, 0) . ". Progress diperbarui.");
    }
}