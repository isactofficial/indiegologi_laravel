@props(['articles'])

<div class="container py-5">
    <h2>Artikel Terbaru</h2>
    <div class="row">
        @forelse ($articles as $article)
            <div class="col-md-4">
                {{-- Buat card untuk setiap artikel di sini --}}
                <div class="card mb-4">
                    <img src="{{ asset('storage/' . $article->thumbnail) }}" class="card-img-top" alt="{{ $article->title }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $article->title }}</h5>
                        <a href="{{ route('front.articles.show', $article->slug) }}" class="btn btn-primary">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
        @empty
            <p>Tidak ada artikel untuk ditampilkan.</p>
        @endforelse
    </div>
</div>