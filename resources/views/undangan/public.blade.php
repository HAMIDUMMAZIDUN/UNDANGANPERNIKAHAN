{{-- 
    File: resources/views/undangan/public.blade.php
    Tujuan: Memanggil template undangan yang benar untuk link umum (grup WA).
--}}
@include('undangan.templates.' . $event->template_name)