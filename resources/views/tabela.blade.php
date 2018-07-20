<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<table class="table">
  <thead>
    <tr>
      @foreach ($headers as $header)
        <th scope="col">{{ $header }}</th>
      @endforeach
    </tr>
  </thead>
  <tbody>
    @foreach ($registros as $offset => $registro)
    <tr>
      @foreach ($registro as $key => $value)
        <td>{{ $value }}</td>
      @endforeach
    </tr>
    @endforeach
  </tbody>
</table>
