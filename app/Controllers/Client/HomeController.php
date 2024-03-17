<?php

namespace App\Controllers\Client;

class HomeController extends UserController
{
    public function index(): void
    {
        $sliders = [
            'images/banner1.png',
            'images/banner2.png',
            'images/banner3.png'
        ];

        $categories = [
            [
                'name' => 'Bánh mì',
                'des' => 'Đa dạng chủng loại từ truyền thống đến sáng tạo, luôn sẵn sàng cho sự lựa chọn của bạn',
                'img' => 'images/bread.png'
            ],
            [
                'name' => 'Bánh ngọt',
                'des' => 'Những chiếc bánh giòn tan với hương vị ngọt ngào, tuyệt vời cho những tính đồ hảo ngọt.',
                'img' => 'images/banhngot.png'
            ],
            [
                'name' => 'Bánh kem nhỏ',
                'des' => 'Đa dạng chủng loại từ truyền thống đến sáng tạo, luôn sẵn sàng cho sự lựa chọn của bạn',
                'img' => 'images/banhkem.png'
            ],
            [
                'name' => 'Sản phẩm đóng gói',
                'des' => 'Sản phẩm đóng gói tiện dụng, nhưng vẫn đảm bảo ngon miệng và dinh dưỡng',
                'img' => 'images/spdonggoi.png'
            ],
            [
                'name' => 'Sản phẩm theo mùa',
                'des' => 'Vào những dịp đặt biệt trong năm, các sản phẩm của chúng tôi sẽ luôn đồng hành cùng bạn.',
                'img' => 'images/sptheomua.png'
            ],
            [
                'name' => 'Bánh kem',
                'des' => 'Với nhiều thiết kế sáng tạo và mới lạ, mỗi chiếc bánh luôn là một kiệt tác',
                'img' => 'images/banhsinhnhat.png'
            ],
        ];

        $data = [
            'sliders' => $sliders,
            'categories' => $categories
        ];
        parent::render(page:'home', data:$data);
    }
}
