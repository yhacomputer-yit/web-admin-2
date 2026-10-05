{{--
    Session flash, as toast markers.

    Rendered once by the admin layout, so no page has to remember to show its own
    flash, and no page can end up with two. toast.js reads these attributes, shows
    the message in the top-right corner and removes the node - which is what stops
    a refresh from replaying it.
--}}
@if (session('success'))
    <span data-flash-toast="{{ session('success') }}" data-flash-type="success" hidden></span>
@endif

@if (session('error'))
    <span data-flash-toast="{{ session('error') }}" data-flash-type="error" hidden></span>
@endif