@if(isset($product) && count($product) > 0)

<div data-repeater-list="invoice" >
@foreach($product as $key => $pro)

    <div data-repeater-item>
        <div class="row d-flex align-items-end dfie ">
            <input type="hidden" class="form-control" name="id" value="{{$pro->id}}" />

            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label for="Size">{{trans('admin.Size')}}</label>
                    <input type="text" class="form-control" aria-describedby="size" placeholder="" name="size"
                        value="{{$pro->size}}" />

                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label for="Weigth">{{trans('admin.Weight')}}</label>
                    <input type="text" class="form-control" aria-describedby="weight" placeholder="" name="weight"
                        value="{{$pro->weight}}" />

                </div>
            </div>

            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label for="Price">{{trans('admin.price')}}</label>
                    <input type="text" class="form-control" aria-describedby="price" placeholder="" name="price"
                        value="{{$pro->price}}" />
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="colorpicker">Product Color</label>
                    <input type="color" id="colorpicker" value="{{$pro->color}}" name="color">
                </div>
            </div>
            <div class="col-md-12 col-12 mb-50">
                <div class="form-group">

                    <button class="btn btn-outline-danger text-nowrap px-1 "  type="button">
                        <i data-feather="x" class="mr-25"></i>
                        <span class="deletefile" data-id="{{$pro->id}}" data-token="{{ csrf_token() }}"
                            data-route="/{{ app()->getLocale() }}/admin/section/posts/DeleteFile/{{ $pro->id }}"
                            delete="{{$pro}}">Delete</span>
                    </button>

                    <button class="btn btn-icon btn-primary" type="button" style="float: right;" data-repeater-create>
                        <i data-feather="plus" class="mr-25"></i>
                        <span>Add New</span>
                    </button>
                </div>
            </div>
        </div>
        <hr />
    </div>
@endforeach
</div>
@else
<div data-repeater-list="invoice">
    <div data-repeater-item>
        <div class="row d-flex align-items-end">

            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label for="Size">{{trans('admin.Size')}}</label>
                    <input type="text" class="form-control" aria-describedby="Size" placeholder="" name="Size"
                        value="" />
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label for="Weigth">{{trans('admin.Weight')}}</label>
                    <input type="text" class="form-control" aria-describedby="Weight" placeholder="" name="Weight"
                        value="" />
                </div>
            </div>

            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label for="Price">{{trans('admin.price')}}</label>
                    <input type="text" class="form-control" aria-describedby="Price" placeholder="" name="Price"
                        value="" />
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="colorpicker">Product Color</label>
                    <input type="color" id="colorpicker" value="#0000" name="Color">
                </div>
            </div>
            <div class="col-md-12 col-12 mb-50">
                <div class="form-group">

                    <button class="btn btn-outline-danger text-nowrap px-1 DeleteProduct" data-repeater-delete
                        type="button">
                        <i data-feather="x" class="mr-25"></i>
                        <span>Delete</span>
                    </button>


                    <button class="btn btn-icon btn-primary" type="button" style="float: right;" data-repeater-create>
                        <i data-feather="plus" class="mr-25"></i>
                        <span>Add New</span>
                    </button>
                </div>
            </div>
        </div>
        <hr />
    </div>
</div>
@endif


{{-- <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>

<script>
    const draggables = document.querySelectorAll('.draggable')
    const containers = document.querySelectorAll('.parent-div')
    draggables.forEach(draggable => {
        draggable.addEventListener('dragstart', () => {
            draggable.classList.add('dragging')
        })
        draggable.addEventListener('dragend', () => {
            draggable.classList.remove('dragging')
        })
    })
    containers.forEach(container => {
        container.addEventListener('dragover', e => {
            e.preventDefault();
            const afterElement = getDragAfterElement(container, e.clientY)
            const draggable = document.querySelector('.dragging')
            container.appendChild(draggable)
        })
    })

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.draggable:not(.dragging)')]
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect()
            const offset = y - box.top - box.height / 2
            console.log(offset)
            if (offset < 0 && offset > closest.offset) {
                return {
                    offset: offset,
                    element: child
                }
            } else {
                return closest
            }
        }, {
            offset: Number.NEGATIVE_INFINITY
        })
    }

</script> --}}

<style>
    .dragblclr {
        background: lightblue;
        margin-bottom: 20px;
    }

    .dragging {
        background: #ff5b5b
    }

</style>
