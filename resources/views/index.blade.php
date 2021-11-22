@extends('layout.master')
@section('heading')
<h3>Dasboard</h3>
@endsection

@section('maincontent')
 <section class="content">
  <div class="row">
    <div class="col-12">
    <button type="button" style="float: right" class="btn btn-success" id="modal"><i class="fa fa-plus"></i> Add Staff</button>

    <div id="myModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="alert alert-danger print-error-msg" style="display:none">
              <ul></ul>
          </div>
          <form id="form1" method="post">
          <div class="modal-body">
          
            <div class="row">
            <div class="col-6">
              <label>NAME</label>
              <input type="text"  id="name" name="name" class="form-control" placeholder="enter full name" required>
            </div>
            <div class="col-6">
              <label>EMAIL</label>
              <input type="email"  id="email" name="email" class="form-control" placeholder="enter email name" required>
            </div>
            <div class="col-6">
              <label>Designation</label>
              <select class="form-control" id="designation" required name="designation">
                  <option value="" selected>Choose Designation</option>
                  @foreach($designation as $des)
                  <option value="{{$des->id}}">{{$des->name}}</option>
                  @endforeach
              </select>
            </div>
            <div class="col-6">
              <label>DEPART MENT</label>
              <input type="file" name="photo" accept=".jpg,.jpeg,.png,.jfif" id="file" class="form-control">
              <div id="previous">
                
              </div>

            </div>
            <input type="hidden" name="category_id" value="2">
            </div>
         
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-warning" id="update" style="display:none">Update info</button>
            <button type="submit" class="btn btn-success" id="add">Add Staff</button>
          </div>
           </form>
           <input type="hidden" id="id">
        </div>
      </div>
    </div> 
    </div>
    <div class="col-12" style="padding-top: 20px;" >
      <table class="table" id="mytable">
        <thead>
          <tr>
          <th>Sl no</th>
          <th>Name</th>
          <th>Email</th>
          <th>Designation</th>
          <th>Image</th>
          <th>Action</th>
       
        </tr>
        </thead>
        <tbody>
  
        </tbody>

      </table>
    </div>
</div>
</section>

@include('layout.script')
<script>
  var path="{{url('')}}";
  var storage=path.replace('public/','');
  var table=$('#mytable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax":path+'/get-employee',
            "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:first", nRow).html(iDisplayIndex +1);
               return nRow;
            },
            "columns": [
             {"data":"name"},
             {"data":"name"},
             {"data":"email"},
             {"data":"designation[0].name"},
             {"data":"photo",render:getImg},
             {"data":"id",render:action},
                
            ],
        });

  function getImg(data, type, full, meta) {
        
        return "<img src='{{url('/image/')}}/"+data+"' height='100px' alt='no image'/>";
        
  }
  function action(data, type, full, meta)
  {
     var op="";
     op+="<i class='fa fa-pen  edit' data-id="+data+"></i>&nbsp;&nbsp;&nbsp;";
     op+="<i class='fa fa-trash  remove' data-id="+data+"></i>";
     return op;

  }


  $('#modal').click(function()
  {  
      $('#update').hide();
      $('#hidden_url').val("");
      $('#add').show();
      $('#form1')[0].reset();
      $('#previous').html("");
      $(".print-error-msg").css('display','none');
      $('#myModal').modal('show')
  })

  $('#form1').submit(function(e)
{
    e.preventDefault();
    var photo = $('#file').prop('files')[0];
    var form_data = new FormData(this);                  
    form_data.append('photo',photo);
    $.ajax({
        url:"{{url('/add-employee')}}",
        method:'POST',
        data:form_data,
        headers:
        {
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr("content")

        },
        processData:false,
        contentType:false,
        success:function(data)
        {
          if(data.success){
            $('#myModal').modal('hide')
            $('#form1')[0].reset();
            alert('successfully Saved')
            table.ajax.reload(null,false);
          }
          else{
              console.log(data);
              printErrorMsg(data.error);
          }


        },
        error:function(err)
        {
            
        }
    })

})
$(document).on('click','.edit',function()
{    
      $(".print-error-msg").css('display','none');
      var id=$(this).data('id');
      $.ajax({
         url:"{{ url('/get-one') }}",
         method:"GET",
         data:{'id':id},
         success:function(data)
         {
            if(data.length>0)
            {
              $('#id').val(id);
              $('#update').show();
              $('#add').hide();
              
              for (var i =0;i<data.length;i++) {
                $('#name').val(data[i].name);
                $('#email').val(data[i].email);
                $('#designation').val(data[i].designation);
                if(data[i].photo!="")
                {
                  op="<a href='{{url('/image/')}}/"+data[i].photo+"' target='_blank'>Click Here To View Previous Image</a>"
                  $('#previous').html(op);
                }
                else
                {
                     $('#previous').html("");
                }

              }
              $('#myModal').modal('show')
            }
         }
         
        });
})

$('#update').click(function(e)
{
  e.preventDefault();

  var photo = $('#file').prop('files')[0];
  var form_data = new FormData(); 
  var name=$('#name').val();
  var email=$('#email').val();
  var id=$('#id').val();
  var designation=$('#designation').val(); 
  form_data.append('photo',photo);
  form_data.append('name',name);
  form_data.append('email',email);
  form_data.append('designation',designation);
  form_data.append('id',id);

  $.ajax({
        url:"{{url('/update-employee')}}",
        method:'POST',
        data:form_data,
        headers:
        {
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr("content")

        },
        processData:false,
        contentType:false,
        success:function(data)
        {
          if(data.success){
            $('#myModal').modal('hide')
            $('#form1')[0].reset();
            alert('successfully Updated')
            table.ajax.reload(null,false);
          }
          else{
              console.log(data);
              printErrorMsg(data.error);
          }

        },
        error:function(err)
        {
            
        }
    })

})

function printErrorMsg (msg) {
      $(".print-error-msg").find("ul").html('');
      $(".print-error-msg").css('display','block');
      $.each( msg, function( key, value ) {
          $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
      });
  }

$(document).on('click','.remove',function()
{    
      $(".print-error-msg").css('display','none');
      var id=$(this).data('id');
      if(confirm("do you want to continue ?"))
      {
        $.ajax({
         url:"{{ url('/remove-employee') }}",
         method:"GET",
         data:{'id':id},
         success:function(data)
         {
             table.ajax.reload(null,false);
         }
         
        });
      }
      
})
</script>
@endsection