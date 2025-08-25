<div class="js-cookie-consent cookie-consent fixed-bottom pb-2">
    <div class="container">
        <div class="p-2 rounded bg-warning">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div class="d-none d-md-flex flex-grow-1 align-items-center">
                    <p class="mb-0 text-dark cookie-consent__message">
                        {!! trans('cookie-consent::texts.message') !!}
                    </p>
                </div>
                <div class="mt-2 mt-sm-0">
                    <button class="js-cookie-consent-agree cookie-consent__agree btn btn-warning text-dark fw-bold">
                        {{ trans('cookie-consent::texts.agree') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
