<?php 

 
print_r($DB->Select("SELECT * FROM nieuws WHERE docent_id = :docent_id AND nieuws_id = :nieuws_id", 
                    ":docent_id, :nieuws_id", 
                    "19, 2"));
    $DB->Update("UPDATE nieuws SET docent_id = :docent_id WHERE nieuws_id = :nieuws_id", ":docent_id, :nieuws_id", "20, 1");

                 /*   print_r($DB->Select("SELECT * FROM docenten_vakken"));


    //$DB->Insert("nieuws", "docent_id", "19");
    //$DB->Delete("nieuws_copy", "docent_id", 19);

    print_r($DB->Select("SELECT * FROM docenten_vakken"));
    */
?>
