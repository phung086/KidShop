<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Dùng cái này để gọi API Google
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function suggest(Request $request)
    {
        $userMessage = trim($request->input('message', ''));

        if (empty($userMessage)) {
            return response()->json(['reply' => 'Chào bạn! Mình có thể giúp gì cho bạn hôm nay?']);
        }

        // 1. TÌM KIẾM SẢN PHẨM (Giữ nguyên)
        $products = Product::query()
            ->where('ProductName', 'like', "%{$userMessage}%")
            ->orWhere('ShortDes', 'like', "%{$userMessage}%")
            ->select(['ProductName', 'Price', 'ShortDes', 'ProductSlug'])
            ->limit(5)
            ->get();

        // 2. TẠO CONTEXT (Giữ nguyên)
        $productContext = "";
        $suggestions = [];
        if ($products->isNotEmpty()) {
            $productContext .= "Dữ liệu sản phẩm tìm thấy:\n";
            foreach ($products as $product) {
                $price = number_format($product->Price, 0, ',', '.');
                $productContext .= "- {$product->ProductName} ({$price} VNĐ)\n";
                $suggestions[] = [
                    'title' => $product->ProductName,
                    'url'   => url('/shop-single/' . $product->ProductSlug),
                    'price' => $price . ' đ'
                ];
            }
        } else {
            $productContext .= "Không có sản phẩm nào khớp tên trong dữ liệu.\n";
        }

        $shopPolicy = "Phí ship 30k. Freeship đơn > 500k. LH: 0909123456.";

        // 3. GỌI GEMINI API
        try {
            // Dán Key của bạn vào đây
            $apiKey = 'AIzaSyAHDAu1NQttO6awNo-6U1HRnaCruVu0vzY';

            $prompt = "Bạn là nhân viên KidolShop. Trả lời ngắn gọn dưới 100 từ.\n" .
                $shopPolicy . "\n" .
                $productContext . "\n" .
                "Khách hỏi: " . $userMessage;

            // --- SỬA QUAN TRỌNG Ở DÒNG NÀY ---
            // Đổi thành: gemini-2.0-flash
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Xin lỗi, mình chưa hiểu ý bạn.';
            } else {
                // In lỗi ra log để kiểm tra nếu cần
                Log::error("Gemini Error: " . $response->body());
                $aiReply = "Hệ thống đang bảo trì. Bạn thử lại sau nhé.";
            }
        } catch (\Exception $e) {
            Log::error("Exception: " . $e->getMessage());
            $aiReply = "Lỗi kết nối máy chủ.";
        }

        return response()->json([
            'reply'       => $aiReply,
            'suggestions' => $suggestions,
        ]);
    }
}
