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
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
    
    <style type="text/css">
    .lllll {
      display: flex;
      flex-direction: column;
    align-items: center;
    justify-content: space-between;
    width: 312px;
    /* background-color: grey; */
    height: 123px;
    flex-wrap: wrap;
    padding: 10px;
    }
    .img_ar {
      width: 44px;
      height: 44px;
      border-radius: 100%;
      -webkit-border-radius:100%;
      -moz-border-radius: 100%;
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

  </head>
  <body>
    <div class="container">
      <h2>Form</h2>
      <!-- Form taking requirements -->
      <form class="form-horizontal generate" method="post" enctype="multipart/form-data">
       
        <div class="form-group">
          <label class="control-label col-sm-2" for="height">Scale</label>
          <div class="col-sm-4">
            <input
              type="text"
              class="form-control"
              id="scale"
              placeholder="1"
              name="scale"
              value="1"
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
            <button type="submit" class="btn btn-default" id="preview">Preview</button>
          </div>
          <div class="col-sm-offset-2 col-sm-10">
            <a href="https://www.google.com" type="button" class="btn btn-default" id="generate">Generate</a>
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
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <!-- <script src="https://superal.github.io/canvas2image/canvas2image.js"></script> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <script>
      $(document).ready(()=>{

        $('.generate').on('submit', function(e){
          e.preventDefault();

          // Get the scale value
          let scale = $('#scale').val();

          // Get the count value
          let count  = $('#count').val();

          // styles would be applied on the of scale
          if(scale>=2){
            $('.lllll').css({
              transform:`scale(${scale})`,
              margin:'10% 20%'
            })
          }
         
        //  Ajaax request for uploading Images
          $.ajax({
                url : "backwork.php",
                method : "POST",
                data : new FormData(this), //data in form format
                // dataType : 'JSON',
                contentType:false,
                processData:false,
                success:function(data){
                  let newData = JSON.parse(data) //parse into JSON

                  var html = '';
                  // <div class="img_ar"></div>
                  // <img src="./img/flower.png" alt="" class="img_val">
                
                  $('.lllll').css('background-image', 'url('+newData.bgUrl+')'); //Update backgroudn URl

                  // Loop through images on the basis of number of counts
                  for(let i = 0; i<count; i++){
                    // $('.img_val').attr('src',newData.imgUrl);
                   
                    html += `
                      <div class="img_ar">
                        <img src="${newData.imgUrl}" alt="" class="img_val">
                      </div>
                    `;
                  }

                  // append into the element that has .lllll class name
                  $('.lllll').html(html);

                    // This libaray help us to canvas by DOM rendered DOM elements
                      html2canvas(document.querySelector(".lllll")).then(canvas => {
                          //  document.body.appendChild(canvas);
                          
                          // Get the canvas data
                           var dataa = canvas.toDataURL('image/png');
                          
                          // Relplace that with Stream/ Alternative of Blob
                           var href = dataa.replace("image/png", "image/octet-stream");
                          
                          //  Assign Image link to Generate Button
                            $('#generate').attr('href', href);

                            // Assign some name and download attribute to a tag that has name of Generate
                            $('#generate').attr('download', 'imagify.png');
                      });

                  console.log(newData)
                }
          })

        })

      });  
    </script>
  </body>
</html>
