<?php

use Illuminate\Support\Facades\Route;
use OpenAI\Laravel\Facades\OpenAI;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('admin.home.index');
});

// Route::get('/test', function () {
//     $result = OpenAI::chat()->create([
//         "model" => "GPT-4o",
//         'messages' => [
//             [
//                 'role' => 'user', 
//                 'content' => 'Tôi là một trung tâm tiếng anh ở Hà Nội với hơn 10 năm hoạt động. Bạn là một chuyên gia content marketing lâu nămchịu trách nhiệm xây dựng nội dung trên nền tảng facebook
// Hãy lên kế hoạch nội dung social trong vòng 1 tháng cho trung tâm của tôi theo 4 pillars chính: 50% bài về Sản phẩm/Dịch vụ, 20% bài Chia sẻ kiến thức, 20% bài về Câu chuyện thương hiệu và 10% bài tương tác.

// Trung tâm của tôi tập trung vào việc dạy tiếng anh giao tiếp, chúng tôi sở hữu đội ngũ giảng viên giỏi. Người học có thể đăng ký học online và offline. Đối tượng là người đi làm, học sinh, sinh viên có nhu cầu cải thiện kỹ năng giao tiếp.

// Kế hoạch nội dung phải mới, sáng tạo, cung cấp giá trị và thu hút nhiều tương tác từ người xem. Hãy trình bày 30 ý tưởng theo dạng bảng, gồm các cột STT, Chủ đề nội dung, Pillar, các pillar xen kẽ nhau cho phù hợp, trả cho tôi dưới dạng file excel'
//             ],
//         ],
//     ]);

//     dd($result);
// });

Route::namespace('Admin')->name('admin.')->prefix('admin')->group(function () {
    require "admin.php";
});
