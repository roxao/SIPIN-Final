<!DOCTYPE html>
<html>
<head>
	<title>cms all</title>
</head>
<body>
	<table id="example1" class="table table-bordered table-striped">
      <thead>
      <tr>               
          <th><center><span class="badge bg-green">id_cms</span></center></th>  
          <th><center><span class="badge bg-brown">content</span></center></th>
          <th><center><span class="badge bg-brown">title</span></center></th>
          <th><center><span class="badge bg-brown">url</span></center></th>
          <th><center><span class="badge bg-brown">created_date</span></center></th>
          <th><center><span class="badge bg-brown">created_by</span></center></th>
          <th><center><span class="badge bg-brown">modified_date</span></center></th>
          <th><center><span class="badge bg-brown">modified_by</span></center></th>
          </tr>
          </thead>
          <tbody>
          <?php
          foreach($cms as $data) 
          { 
          ?>
            <tr>
            <td><center><?php echo $data->id_cms;?></center></td>
            <td><center><?php echo $data->content;?></center></td>
            <td><center><?php echo $data->title;?></center></td>
            <td><center><?php echo $data->url;?></center></td>
            <td><center><?php echo $data->created_date;?></center></td>
            <td><center><?php echo $data->created_by;?></center></td>
            <td><center><?php echo $data->modified_date;?></center></td>
            <td><center><?php echo $data->modified_by;?></center></td>
            <td>

            </tr>

            <?php } ?>  
            </tbody>
</table>
</body>
</html>