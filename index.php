<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <title>Web Graph</title>
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.0/dist/chart.min.js"></script>
    <meta http-equiv="refresh" content="15"; url=" <?php echo $_SERVER['PHP_SELF']; ?>">
  </head>

  <body>
    <h1>62105689 Pawaris Kongjan</h1>
    
    <div class="container">
      <div class="class row">
        <div class="class col-4">
          <canvas id="myChart" width="400" height="200"></canvas>
        </div>
        <div class="class col-4">
          <canvas id="myChart2" width="400" height="200"></canvas>
        </div>
        <div class="class col-4">
          <canvas id="myChart3" width="400" height="200"></canvas>
        </div>

      </div>

      <div class="class row">
        <div class="class col-3">

          <div class="class row">
            <div class="class col-4"><b>Temperature</b></div>
            <div class="col-8" >
              <span id="lastTemperature"></span>
            </div>
          </div>

          <div class="class row">
            <div class="class col-4"><b>Humidity</b></div>
            <div class="col-8" >
              <span id="lastHumidity"></span>
            </div>
          </div>

          <div class="class row">
            <div class="class col-4"><b>Light_Status</b></div>
            <div class="col-8" >
              <span id="lightStatus"></span>
            </div>
          </div>


          <div class="class row">
            <div class="class col-4"><b>Update</b></div>
            <div class="col-8" >
              <span id="lastUpdate"></span>
            </div>
          </div>

        </div>
      </div>
    </div>


  </body>
  <script>

    function showChart(data){
      var ctx=document.getElementById('myChart').getContext('2d');
      var myChart=new Chart(ctx,{
          type:'line',
          data:{
            labels:data.xlabel,
            datasets:[{
                label:data.label,
                data:data.data
            }]
          }
      });
    }

    function showLine(data2){
      var ctxy=document.getElementById('myChart2').getContext('2d');
      var myChart=new Chart(ctxy,{
          type:'line',
          data:{
            labels:data2.xlabel,
            datasets:[{
                label:data2.label,
                data:data2.data
            }]
          }
      });
    }

    function showGraph(data3){
      var ctxyz=document.getElementById('myChart3').getContext('2d');
      var myChart=new Chart(ctxyz,{
          type:'line',
          data:{
            labels:data3.xlabel,
            datasets:[{
                label:data3.label,
                data:data3.data
            }]
          }
      });
    }
      
      
     
      $(()=>{
          
          let url="https://api.thingspeak.com/channels/1458413/feeds.json?results=240";
          $.getJSON(url)
            .done(function(data){
              //console.log(data);
              let feed=data.feeds;
              let chan=data.channel;
              console.log(feed);

              const d =new Date(feed[0].created_at);
              const monthNames=["January","February","March","April","May","June","July"
                                ,"August","September","October","November","December"];
              let dateStr = d.getDate()+" "+monthNames[d.getMonth()]+" "+d.getFullYear();
              dateStr += " "+d.getHours()+":"+d.getMinutes();
              


             var plot_data=Object();
             var xlabel=[];
             var temp=[];
             var humi=[];
             var light=[];
             var light_status;
             var dates=[];
  
             
             $.each(feed,(k,v)=>{
                xlabel.push(v.entry_id);
                humi.push(v.field1);
                temp.push(v.field2);
                light.push(v.field3);
                dates.push(v.created_at);
                if(light[k]>500){
                  light_status="dark";
                }else {
                  light_status="light";
                }
                




              $("#lastTemperature").text(temp[k]+" C");
              //$("#lastTemperature").text(feed[k].field2+" C");
              $("#lastHumidity").text(humi[k]+" %");
              $("#lightStatus").text(light_status);
              $("#lastUpdate").text(dateStr);

                
             });
             
             
              var data=new Object();
              data.xlabel=xlabel;
              data.data=temp;
              data.label=chan.field2;
              showChart(data);

            
              var data2=new Object();
              data2.xlabel=xlabel;
              data2.data=humi;
              data2.label=chan.field1;
              showLine(data2);

              var data3=new Object();
              data3.xlabel=xlabel;
              data3.data=light;
              data3.label=chan.field3;
              showGraph(data3);

            })
            .fail(function(error){
            });
      });
    
  </script>
</html>