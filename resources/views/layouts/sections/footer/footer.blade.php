<!-- Footer-->
<footer class="content-footer footer bg-footer-thdeme" style="background-color: transparent">
  <div class="{{ (!empty($containerNav) ? $containerNav : 'container-fluid') }} d-flex flex-wrap justify-content-between py-0 flex-md-row flex-column">
    <div class="mb-0 mb-md-0 mt-3" >
      © <script>
        document.write(new Date().getFullYear())

      </script>
      , made by <a href="{{ (!empty(config('variables.creatorUrl')) ? config('variables.creatorUrl') : '') }}" target="_blank" class="footer-link fw-semibold">{{ (!empty(config('variables.creatorName')) ? config('variables.creatorName') : '') }}</a>
    </div>
    <div>
      <a href="{{ config('variables.documentation') ? config('variables.documentation') : '#' }}" target="_blank" class="footer-link me-4">

      @include('_partials.macrosadcockflat')
      </a>
    </div>
  </div>
</footer>
<!--/ Footer-->
