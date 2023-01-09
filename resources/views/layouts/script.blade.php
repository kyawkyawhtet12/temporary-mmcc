 <!-- JAVASCRIPT -->
 <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
 <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
 <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>
 <script src="{{asset('assets/libs/feather-icons/feather.min.js')}}"></script>
 <script src="{{asset('assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
 <script src="{{asset('assets/js/plugins.js')}}"></script>

 <!-- apexcharts -->
 <script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>

 <!-- Vector map-->
 {{-- <script src="{{asset('assets/libs/jsvectormap/js/jsvectormap.min.js')}}"></script>
 <script src="{{asset('assets/libs/jsvectormap/maps/world-merc.js')}}"></script> --}}

 <!--Swiper slider js-->
 {{-- <script src="{{asset('assets/libs/swiper/swiper-bundle.min.js')}}"></script> --}}

 <!-- Dashboard init -->
 {{-- <script src="{{asset('assets/js/pages/dashboard-ecommerce.init.js')}}"></script> --}}

 <!-- App js -->
 <script src="{{asset('assets/js/app.js')}}"></script>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.0/jquery.min.js" integrity="sha512-suUtSPkqYmFd5Ls30Nz6bjDX+TCcfEzhFfqjijfdggsaFZoylvTj+2odBzshs0TCwYrYZhQeCgHgJEkncb2YVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

 <script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
 <script src="{{ asset('assets/js/jquery.tabledit.min.js') }}"></script>

<script src="{{ asset('assets/backend/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assets/backend/vendors/js/vendor.bundle.addons.js') }}"></script>
<script src="{{ asset('assets/backend/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/backend/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('assets/backend/js/misc.js') }}"></script>
<script src="{{ asset('assets/backend/js/settings.js') }}"></script>
<script src="{{ asset('assets/backend/js/todolist.js') }}"></script>
<script src="{{ asset('assets/backend/js/dashboard.js') }}"></script>
{{-- <script src="{{ asset('assets/backend/js/data-table.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/backend/js/select2.js') }}"></script> --}}
<script src="{{ asset('assets/backend/js/js-grid.js') }}"></script>
<script src="{{ asset('assets/backend/js/db.js') }}"></script>
<script src="{{ asset('assets/backend/js/formpickers.js') }}"></script>
<script src="{{ asset('assets/backend/js/form-addons.js') }}"></script>
<script src="{{ asset('assets/backend/js/x-editable.js') }}"></script>
<script src="{{ asset('assets/backend/js/dropify.js') }}"></script>
<!-- <script src="{{ asset('assets/backend/js/dropzone.js') }}"></script> -->
<script src="{{ asset('assets/backend/js/jquery-file-upload.js') }}"></script>
<script src="{{ asset('assets/backend/js/formpickers.js') }}"></script>
<script src="{{ asset('assets/backend/js/form-repeater.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{ asset('assets/backend/js/date-range.js') }}"></script>
<script src="{{ asset('assets/backend/js/jquery.tabledit.min.js') }}"></script>
<script src="{{ asset('assets/backend/js/file-upload.js') }}"></script>
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js"></script>


 @yield('script')
 @stack('scripts')
