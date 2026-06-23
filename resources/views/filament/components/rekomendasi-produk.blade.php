<div class="space-y-3">

    @foreach($produk as $index => $item)

        <div class="flex items-center gap-3 p-3 rounded-xl border">

            <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center font-bold">
                {{ $index + 1 }}
            </div>

            <img
                src="{{ asset('storage/' . $item->gambar) }}"
                class="w-14 h-14 rounded-lg object-cover"
            >

            <div class="flex-1">

                <div class="font-semibold">
                    {{ $item->nama_kue }}
                </div>

                <div class="text-sm text-gray-500">
                    Terjual {{ $item->total_terjual }} pcs
                </div>

            </div>

            @if($index == 0)
                <span class="px-3 py-1 text-xs bg-pink-100 rounded-full">
                    Paling Laris
                </span>
            @endif

        </div>

    @endforeach

</div>