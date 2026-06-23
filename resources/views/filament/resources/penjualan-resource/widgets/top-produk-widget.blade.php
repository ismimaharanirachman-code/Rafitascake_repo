<x-filament-widgets::widget>
    <x-filament::section style="background: #fff5f7; border: 1px solid #fce7f3; border-radius: 24px; padding: 24px;">

        <div style="
            background: linear-gradient(135deg, #ffffff 0%, #fff1f2 100%);
            border: 2px solid #fbcfe8;
            padding: 22px;
            border-radius: 20px;
            margin-bottom: 32px;
            text-align: center;
            box-shadow: 0 10px 25px -5px rgba(251, 207, 232, 0.5);
            position: relative;
        ">
            <span style="position: absolute; top: 12px; left: 20px; font-size: 20px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.05));">✨</span>
            <span style="position: absolute; bottom: 12px; right: 20px; font-size: 20px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.05));">✨</span>

            <h2 style="font-size: 22px; font-weight: 800; color: #9d174d; margin: 0; letter-spacing: -0.02em; font-family: 'Plus Jakarta Sans', 'Nunito', sans-serif;">
                🎀 Menu Terlaris Rafitas Cake 🎀
            </h2>
            <p style="margin: 6px 0 0 0; color: #db2777; font-size: 13px; font-weight: 600; opacity: 0.8;">
                Tiga produk paling dicintai dan paling banyak diborong oleh pelanggan setia~
            </p>
        </div>

       
        <div style="
            display: flex;
            gap: 28px;
            flex-wrap: wrap;
            justify-content: center;
        ">

            @foreach ($produk as $index => $item)
                @php
                    // Pengaturan dekorasi warna pink estetik berdasarkan peringkat
                    $isFirst = $index == 0;
                    $cardBg = $isFirst ? '#ffffff' : '#ffffff';
                    $borderColor = $isFirst ? '#f43f5e' : '#fbcfe8';
                    $badgeText = '';
                    $badgeEmoji = '';
                    
                    if ($index == 0) {
                        $badgeText = '👑 Best Seller';
                    } elseif ($index == 1) {
                        $badgeText = '💕 Best Seller';
                    } elseif ($index == 2) {
                        $badgeEmoji = '🍪';
                    }
                @endphp

                <div class="cute-luxury-card" style="
                    width: 245px;
                    background: {{ $cardBg }};
                    border: 2px solid {{ $borderColor }};
                    border-radius: 22px;
                    overflow: hidden;
                    box-shadow: 0 8px 20px -4px rgba(251, 207, 232, 0.4);
                    display: flex;
                    flex-direction: column;
                    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                ">
   
                    <style>
                        .cute-luxury-card:hover {
                            transform: translateY(-8px) scale(1.02);
                            border-color: #db2777 !important;
                            box-shadow: 0 20px 30px -5px rgba(219, 39, 119, 0.25) !important;
                        }
                    </style>

                    <div style="position: relative; width: 100%; height: 160px; background: #fff5f7; overflow: hidden;">
                   
                        <div style="
                            position: absolute;
                            top: 12px;
                            left: 12px;
                            background: rgba(255, 255, 255, 0.9);
                            backdrop-filter: blur(8px);
                            color: #db2777;
                            padding: 5px 12px;
                            border-radius: 12px;
                            font-size: 11px;
                            font-weight: 800;
                            border: 1px solid #fbcfe8;
                            box-shadow: 0 4px 10px rgba(219, 39, 119, 0.05);
                            z-index: 5;
                        ">
                            <span style="margin-right: 3px;">{{ $badgeEmoji }}</span> {{ $badgeText }}
                        </div>

  
                        @if($item->gambar)
                            <img
                                src="{{ asset('storage/'.$item->gambar) }}"
                                style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease;"
                                onmouseover="this.style.transform='scale(1.05)'"
                                onmouseout="this.style.transform='scale(1)'"
                                alt="{{ $item->nama_kue }}"
                            >
                        @else

                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #f43f5e; background: linear-gradient(135deg, #fff5f7 0%, #ffe4e6 100%);">
                                <span style="font-size: 36px; margin-bottom: 4px; filter: drop-shadow(0 2px 4px rgba(219,39,119,0.1));">🍰</span>
                                <span style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Belum ada foto~</span>
                            </div>
                        @endif
                    </div>

                    <div style="padding: 18px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; background: #ffffff;">
 
                        <h3 style="
                            font-size: 15px;
                            font-weight: 800;
                            text-align: center;
                            color: #4c0519;
                            margin: 0 0 16px 0;
                            line-height: 1.5;
                            display: -webkit-box;
                            -webkit-line-clamp: 2;
                            -webkit-box-orient: vertical;
                            overflow: hidden;
                            height: 44px;
                        ">
                            {{ $item->nama_kue }}
                        </h3>

                        <div style="
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
                            padding: 10px 14px;
                            border-radius: 14px;
                            border: 1px dashed #f43f5e;
                        ">
                            <span style="font-size: 12px; color: #9d174d; font-weight: 700;">Sudah Terjual</span>
                            <div style="display: flex; align-items: baseline; gap: 2px;">
                                <span style="font-size: 18px; color: #e11d48; font-weight: 900; letter-spacing: -0.02em;">
                                    {{ number_format($item->total_terjual, 0, ',', '.') }}
                                </span>
                                <span style="font-size: 11px; font-weight: 700; color: #be123c;">pcs</span>
                            </div>
                        </div>

                    </div>

                </div>
            @endforeach

        </div>

    </x-filament::section>
</x-filament-widgets::widget>