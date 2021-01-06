<?php 

    if(isset($_POST['submit']))
    {
        $rules = array(   
            'text' => array(
                'type' => 'string',
                'verplicht' => true
            )
         );
         


        var_dump($filter->validateFields($rules));
    }    

?>
<div class="mainWrapper"> 
    <main>
        <form method="post">
            <input type="text" name="text">
            <input type="submit" name="submit">
        </form>
    </main>
</div>
