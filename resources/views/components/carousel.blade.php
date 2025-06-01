<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
    <?php
        $i = 0;
        foreach ($sliders as $slider) {
    ?>
      <div class="carousel-item <?= $i == 0 ? 'active' : '' ?>">
        <img src="{{ url('storage/'.$slider->image) }}" class="d-block w-100" alt="{{ $slider->title }}">
        <div class="carousel-caption d-lg-none d-flex">
          <h1 class="text-dark display-6">{{ $slider->title }}</h1>
          <div class="text-dark d-none d-md-block">{{ $slider->subtitle }}</div>
          <div class="mt-2">
            <a href="" class="btn btn-sm btn-primary">Get Started</a>
          </div>
        </div>
        <div class="carousel-caption d-none d-lg-flex">
          <h1 class="text-dark display-4 mt-lg-5">{{ $slider->title }}</h1>
          <div class="text-dark ">{{ $slider->subtitle }}</div>
          <div class="mt-4">
            <a href="" class="btn btn-primary px-5">Get Started</a>
          </div>
        </div>
      </div>
    <?php $i++;} ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
</div>