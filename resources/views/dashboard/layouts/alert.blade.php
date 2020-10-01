@if ($errors->any())
<script>
    Snackbar.show({
        text: 'Danger',
        actionTextColor: '#fff',
        backgroundColor: '#e7515a',
        text: '{{ $errors->first() }}',
        pos: 'bottom-right',
        duration: 5000,
        showAction: false
    });
</script>
@endif


@if (session()->has('danger'))
<script>
    Snackbar.show({
        text: 'Danger',
        actionTextColor: '#fff',
        backgroundColor: '#e7515a',
        text: "{{ session('danger') }}",
        pos: 'bottom-right',
        duration: 5000,
        showAction: false
    });
</script>
@endif

@if (session()->has('success'))
<script>
    Snackbar.show({
        text: 'Success',
        actionTextColor: '#fff',
        backgroundColor: '#8dbf42',
        text: "{{ session('success') }}",
        pos: 'bottom-right',
    });
</script>
@endif