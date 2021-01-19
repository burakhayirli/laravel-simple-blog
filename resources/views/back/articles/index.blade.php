@extends('back.layouts.master')
@section('title','Tüm Makaleler')
@section('content')

  <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold  text-primary">@yield('title')
        <strong><span class="float-right">{{$articles->count()}}  makale bulundu.
            <a href="{{route('admin.trashed.article')}}" class="btn btn-warning"><i class="fa fa-trash"></i>Silinen Makaleler</a>
            </span>
        </strong>


      </h6>
      </div>
      <div class="card-body">
          <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                      <tr>
                         <th>Fotoğraf</th>
                          <th>Makale Başlığı</th>
                          <th>Kategori</th>
                          <th>Görüntülenme</th>
                          <th>Oluşturulma Tarihi</th>
                          <th>Durum</th>
                          <th>İşlemler</th>
                      </tr>
                  </thead>
                  <tfoot>
                      <tr>
                          <th>Name</th>
                          <th>Position</th>
                          <th>Office</th>
                          <th>Age</th>
                          <th>Start date</th>
                          <th>Salary</th>
                          <th>Salary</th>
                      </tr>
                  </tfoot>
                  <tbody>
                    @foreach ($articles as $article)
                      <tr>
                          <td>
                            <img src="{{$article->image}}" width="200">
                          </td>
                          <td>{{$article->title}}</td>
                          <td>{{$article->getCategory->name}}</td>
                          <td>{{$article->hit}}</td>
                          <td>{{$article->created_at->diffForHumans()}}</td>
                          <td>
                            <input class="switch" article-id="{{$article->id}}" type="checkbox" data-on="Aktif" data-off="Pasif" data-onstyle="success"
                            data-offstyle="danger" @if($article->status==1) checked @endif data-toggle="toggle" >

                          </td>
                          <td>
                            <a target="_blank" href="{{route('single',[$article->getCategory->slug,$article->slug])}}" title="Görüntüle" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>
                            <a href="{{route('admin.makaleler.edit',$article->id)}}" title="Düzenle" class="btn btn-sm btn-primary"><i class="fa fa-pen"></i></a>
                            <a href="{{route('admin.delete.article',$article->id)}}" title="Sil" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></a>

                          </td>
                      </tr>
                    @endforeach

                  </tbody>
              </table>
          </div>
      </div>
  </div>

@endsection
@section('css')
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endsection
@section('js')
  <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
  <script>
  $(function() {
    $('.switch').change(function() {
      //$('#console-event').html('Toggle: ' + $(this)[0].getAttribute('article-id'));
      id=$(this)[0].getAttribute('article-id');
      status=$(this).prop('checked');
      $.get("{{route('admin.switch')}}",{id:id,status:status},function(data,status){
        //console.log(data);
        //console.log(status);

      });
    })
  });
</script>
@endsection
