<!DOCTYPE html>
<html lang="en">
  <head>
    <title>laravel</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"
    />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  </head>
  <style type="text/css">
    .lllll {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 33.3333%;
      background-color: grey;
      height: 100px;
    }
    .img_ar {
      width: 44px;
      height: 44px;
      border-radius: 100%;
      border: 2px solid #000;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }
    .img_ar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  </style>

  <body>
    <div class="container">
      <h2>Form</h2>
      <form class="form-horizontal generate" method="post" enctype="multipart/form-data">
       
        <div class="form-group">
          <label class="control-label col-sm-2" for="width">Width:</label>
          <div class="col-sm-4">
            <input
              type="text"
              class="form-control"
              id="width"
              placeholder="width"
              name="width"
            />
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="height">Height:</label>
          <div class="col-sm-4">
            <input
              type="text"
              class="form-control"
              id="height"
              placeholder="height"
              name="height"
            />
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="image">Image:</label>

          <div class="col-sm-4">
            <input type="file" name="image" class="form-control-file" id="image" />
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="bg_image">bg_image:</label>

          <div class="col-sm-4">
            <input type="file" class="form-control-file" name="bg_image" id="bg_image" />
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="count">Count:</label>
          <div class="col-sm-4">
            <input
              type="text"
              class="form-control count"
              id="count"
              placeholder="count"
              name="count"
            />
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default" id="generate">Submit</button>
          </div>
        </div>
      </form>
    </div>

    <div class="container">
      <div class="lllll">
        <!-- Append from res -->
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
      $(document).ready(()=>{

        $('.generate').on('submit', function(e){
          e.preventDefault();

          let count  = $('#count').val();
         
          $.ajax({
                url : "backwork.php",
                method : "POST",
                data : new FormData(this),
                // dataType : 'JSON',
                contentType:false,
                processData:false,
                success:function(data){
                  let newData = JSON.parse(data)

                  var html = '';
                  // <div class="img_ar"></div>
                  // <img src="./img/flower.png" alt="" class="img_val">
                
                  $('.lllll').css('background-image', 'url('+newData.bgUrl+')');

                  for(let i = 0; i<count; i++){
                    // $('.img_val').attr('src',newData.imgUrl);
                    html += `
                      <div class="img_ar">
                        <img src="${newData.imgUrl}" alt="" class="img_val">
                      </div>
                    `;
                  }

                  $('.lllll').html(html);

                  console.log(newData)
                }
          })

         

        })

      // document.getElementById("generate").addEventListener("click", function(e) {
      //   e.preventDefault();
      //   const imgFile = document.getElementById('image').files[0];
      //   console.log(imgFile)

      //   document.querySelector('.img_val').src = imgFile.name;


      });
      
    </script>
  </body>
</html>
