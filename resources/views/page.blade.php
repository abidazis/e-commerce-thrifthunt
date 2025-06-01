<x-layout :$menu :$submenu :$categories :$appname>

    <header class="sec-header" style="background-image: url({{ url('storage/'.$page->image) }});">
        <h1 class="display-4">{{ $page->title }}</h1>
    </header>

    <section class="py-5 page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <?= $page->description ?>
                </div>
            </div>
        </div>
    </section>

</x-layout>