<div class="modal fade" id="deleteConfirm" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-capitalize py-1" id="modelHeading">
                    Confirm ?
                </h6>
            </div>
            <div class="modal-body">
                <form name="form" class="form-horizontal" method="POST">

                    @csrf

                    @method('delete')

                    <div class="py-5 text-center">
                        <h6> Are you sure to delete this betting record ? </h6>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="#" class="btn btn-outline-danger mx-3 cancel-delete">
                            No
                        </a>
                        <button type="submit" class="btn btn-success" id="submit-btn">
                            Yes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@push('scripts')
    <script>
        $('body').on('click', '.delete-record', function() {

            let route = $(this).data('route');

            $("#deleteConfirm form").attr('action', route);

            $("#deleteConfirm").modal('show');

        });

        $('body').on('click', '.cancel-delete', function() {

            $("#deleteConfirm form").attr('action', '');

            $("#deleteConfirm").modal('hide');

        });

        $('body').on('click', '.submit-btn', function() {

            $("#deleteConfirm .submit-btn").attr("disabled", true);

            $("#deleteConfirm").modal('hide');

        });

    </script>
@endpush
