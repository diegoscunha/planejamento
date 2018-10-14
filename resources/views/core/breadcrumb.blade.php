@isset($breadcrumb)
<ol class="breadcrumb">
  @foreach($breadcrumb as $key => $link)
    @if(!$loop->last)
    <li class="breadcrumb-item"><a href="{{ $link }}">{{ $key }}</a></li>
    @else
    <li class="breadcrumb-item active">{{ $key }}</li>
    @endif
  @endforeach
</ol>
@endisset
