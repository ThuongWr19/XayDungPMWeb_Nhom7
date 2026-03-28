<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index() {
        // Lấy danh sách kỳ thi kèm theo số lượng câu hỏi đã được random
        return response()->json(Exam::withCount('questions')->orderBy('id', 'desc')->get());
    }

    public function store(Request $request) {
        $data = $request->validate([
            'title' => 'required|string',
            'subject' => 'required|string',
            'duration' => 'required|integer',
            'total_questions' => 'required|integer',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'password' => 'nullable|string',
        ]);
        $data['is_active'] = false; // Mặc định tạo ra là Đóng
        
        $exam = Exam::create($data);
        return response()->json(['message' => 'Tạo kỳ thi thành công!', 'exam' => $exam]);
    }

    public function update(Request $request, $id) {
        $exam = Exam::findOrFail($id);
        $exam->update($request->all());
        return response()->json(['message' => 'Cập nhật thành công!']);
    }

    public function destroy($id) {
        Exam::destroy($id);
        return response()->json(['message' => 'Xóa thành công!']);
    }

    // API Đóng/Mở kỳ thi
    public function toggleStatus($id) {
        $exam = Exam::findOrFail($id);
        $exam->is_active = !$exam->is_active;
        $exam->save();
        return response()->json(['message' => 'Đã thay đổi trạng thái kỳ thi!', 'is_active' => $exam->is_active]);
    }

    // API Random câu hỏi
    public function generateQuestions($id) {
        $exam = Exam::findOrFail($id);
        
        // Lấy ngẫu nhiên các câu hỏi thuộc môn học của kỳ thi
        $randomQuestions = Question::where('subject', $exam->subject)
                                   ->inRandomOrder()
                                   ->limit($exam->total_questions)
                                   ->pluck('id');

        // Kiểm tra xem ngân hàng câu hỏi có đủ số lượng không
        if ($randomQuestions->count() < $exam->total_questions) {
            return response()->json([
                'error' => 'Ngân hàng câu hỏi môn "' . $exam->subject . '" chỉ có ' . $randomQuestions->count() . ' câu, không đủ ' . $exam->total_questions . ' câu yêu cầu!'
            ], 400);
        }

        // Đồng bộ vào bảng trung gian (xóa câu cũ, đắp câu mới vào)
        $exam->questions()->sync($randomQuestions);

        return response()->json(['message' => 'Đã tạo xong đề thi ngẫu nhiên với ' . $exam->total_questions . ' câu hỏi!']);
    }
}