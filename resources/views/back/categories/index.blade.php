@extends('back.layouts.master')
@section('title','Tüm Kategoriler')
@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Yeni Kategori Oluştur</h6>
      </div>
      <div class="card-body">
        <form method="post" action="{{route('admin.category.create')}}">
          {{csrf_field()}}
          <div class="form-group">
            <label>Kategori Adı</label>
            <input type="text" class="form-control" name="category" required />
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Ekle</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"> @yield('title')</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Kategori Adı</th>
                <th>Makale Sayısı</th>
                <th>Durum</th>
                <th>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($categories as $category)
              <tr>
                <td>{{$category->name}}</td>
                <td>{{$category->articleCount()}}</td>
                <td>
                  <input class="switch" category-id="{{$category->id}}" type="checkbox" data-on="Aktif" data-off="Pasif" data-onstyle="success" data-offstyle="danger" @if($category->status==1) checked
                  @endif data-toggle="toggle" >
                </td>
                <td>
                  <a category-id="{{$category->id}}" class="btn btn-sm btn-primary edit-click" title="Kategoriyi Düzenle"><i class="fa fa-edit text-white"></i></a>
                  <a category-id="{{$category->id}}" category-name="{{$category->name}}" category-count="{{$category->articleCount()}}" class="btn btn-sm btn-danger remove-click" title="Kategoriyi Sil"><i class="fa fa-times text-white"></i></a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Modal Kategori Düzenle -->
<div id="editModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Kategoriyi Düzenle</h4>
      </div>
      <div class="modal-body">
        <p>
          <form method="post" action="{{route('admin.category.update')}}">
            {{csrf_field()}}
            <div class="form-group">
              <label>Kategori Adı</label>
              <input  id="category" type="text" class="form-control" name="category"/>
              <input type="hidden" name="id" id="category-id"/>
            </div>
            <div class="form-group">
              <label>Kategori Slug</label>
              <input id="slug" type="text" class="form-control" name="slug"/>
            </div>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
        <button type="submit" class="btn btn-success">Kaydet</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal Kategori Sil -->
<div id="deleteModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Kategoriyi Sil</h4>
      </div>
      <div id="body" class="modal-body">
        <div class="alert alert-danger" id="articleAlert"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
        <form method="post" action="{{route('admin.category.delete')}}">
          {{csrf_field()}}
          <input type="hidden" name="id" id="deleteId" />
          <button  id="deleteButon" type="submit" class="btn btn-success">Sil</button>
        </form>
      </div>
      </form>
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
    $('.edit-click').click(function() {
      id = $(this)[0].getAttribute('category-id');
      $.ajax({
        type: 'GET',
        url: '{{route('admin.category.getdata')}}',
        data: {
          id: id
        },
        success: function(data) {
          $('#category').val(data.name);
          $('#slug').val(data.slug);
          $('#category-id').val(data.id);
          $('#editModal').modal();
        }
      });
    });


    $('.remove-click').click(function() {
      id = $(this)[0].getAttribute('category-id');
      count = $(this)[0].getAttribute('category-count');
      name = $(this)[0].getAttribute('category-name');
      if(id==1){
        $('#articleAlert').html(name+' kategorisi sabit kategoridir. Silinen diğer kategorilere ait makaleler bu kategoriye eklenecektir.');
        $('#body').show();
        $('#deleteButon').hide();
        $('#deleteModal').modal();
        return;
      }

      $('#deleteId').val(id);
      $('#articleAlert').html('');
      $('#body').hide();
      $('#deleteButon').show();
      if(count>0){
        $('#articleAlert').html('Bu kategoiye ait '+count+' adet makale bulunmaktadır. Silmek istediğinizden emin misiniz?');
        $('#body').show();
      }
     $('#deleteModal').modal();
    });


    $('.switch').change(function() {
      //$('#console-event').html('Toggle: ' + $(this)[0].getAttribute('article-id'));
      id = $(this)[0].getAttribute('category-id');
      status = $(this).prop('checked');
      $.get("{{route('admin.category.switch')}}", {
        id: id,
        status: status
      }, function(data, status) {
        //console.log(data);
        //console.log(status);

      });
    })
  });
</script>
@endsection
