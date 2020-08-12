window.csrf = function () {
    return $('meta[name="csrf-token"]').attr('content');
}