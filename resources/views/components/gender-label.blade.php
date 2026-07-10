@php
    $v = $value ?? null;
    $norm = strtolower(trim((string)($v ?? '')));
    $male = ['l','laki','laki-laki','laki laki','male'];
    $female = ['p','perempuan','wanita','female'];

    if (in_array($norm, $male, true)) {
        echo 'Laki-laki';
    } elseif (in_array($norm, $female, true)) {
        echo 'Perempuan';
    } elseif (!empty($v)) {
        echo ucfirst($v);
    } else {
        echo '-';
    }
@endphp
