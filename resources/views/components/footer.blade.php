<footer class="py-5 bg-dark">
    <div class="container">
      <div class="row">
        <div class="col-md-4 col-lg-2 mb-3 mb-md-0 col-6">
          <h5 class="text-light">Category</h5>
          <ul class="nav flex-column">
            @foreach($categories as $c)            
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">{{ $c->title }}</a></li>
            @endforeach
          </ul>
        </div>
  
        <div class="col-md-4 col-lg-2 mb-3 mb-md-0 col-6">
          <h5 class="text-light">Pages</h5>
          
          <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">Home</a></li>
            @foreach($menu as $mn)
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">{{ $mn->name }}</a></li>
            @endforeach
          </ul>
        </div>
  
        <div class="col-md-4 col-lg-2 mb-3 mb-md-0 col-6">
          <h5 class="text-light">Other</h5>
          <ul class="nav flex-column">
            @foreach($submenu as $mn)
            <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-muted">{{ $mn->name }}</a></li>
            @endforeach
          </ul>
        </div>
  
        <div class="col-lg-4 offset-lg-1 mt-3 mt-lg-0">
          <form>
            <h5 class="text-light">Subscribe to our newsletter</h5>
            <p class="text-light">Monthly digest of whats new and exciting from us.</p>
            <div class="d-flex w-100 gap-2">
              <label for="newsletter1" class="visually-hidden">Email address</label>
              <input id="newsletter1" type="text" class="form-control" placeholder="Email address">
              <button class="btn btn-primary" type="button">Subscribe</button>
            </div>
          </form>
        </div>
      </div>
  
      <div class="row">
        <div class="col-12">
          <div class="text-center py-4 my-4 border-top">
            <p class="text-light">&copy; 2021 Company, Inc. All rights reserved.</p>
          </div>
        </div>
      </div>
    </div>
  </footer>