<?php
/**
 * Created by PhpStorm.
 * User: Salchegger Robert
 * Date: 17.01.2019
 * Time: 08:08
 */





if(isset($_POST['search']))
{
    echo '<h2>Nach Rezepten suchen</h2>';
    try {
        $rezname = $_POST['rezname'];
        echo '<h4>Gesucht wurde nach: '.$rezname.'</h4>';
        $query = 'select * from rezeptname where rez_name like ?';
        $rezname = '%'.$rezname.'%';
        $rezarray = array($rezname);
        $stmt = $con->GetStatement($query, $rezarray);
        $count = $stmt->rowCount();

        if($count == null) throw new Exception('<h4>Die Suchanfrage brachte keine Ergebnisse</h4>');
        ?>

        <form method="post">
            <div class="table">
                <div class="tr">
                    <div class="th">
                        <label for="suche">Ergebnisliste der Suche:</label>
                    </div>
                    <div class="td">

                        <select name="rezid">
                        <?php

                        while($row = $stmt->fetch(PDO::FETCH_NUM))
                        {
                            echo '<option value="'.$row[0].'">'.$row[1];

                        }
                        ?>
                        </select>

                    </div>
                </div>
                <div class="tr">
                    <div class="td">
                        <input type="submit" name="show" value="anzeigen">
                    </div>
                </div>
            </div>
        </form>
        <?php


    } catch (Exception $e)
    {
        echo $e->getMessage();
    }
} else if(isset($_POST['show'])) {

    try {
        $rezid = $_POST['rezid'];
        $rezid_suche = array($rezid);
        $query2 = 'select rez_name from rezeptname where rez_id=?';
        $stmt = $con->GetStatement( $query2, $rezid_suche);
        $rezname = $stmt->fetch();
        $query = 'select * from zubereitung where rez_id=? order by rez_id';

        $stmt = $con->GetStatement($query, $rezid_suche);
        echo '<h4>Alle Ergebnisse für '.$rezname[0].':</h4>';
        while($row = $stmt->fetch(PDO::FETCH_NUM))
        {

            echo '<hr>Rezeptnummer '.$row[0].': '.$row[1];
            $query1 = 'select zubein_menge as Menge, ein_name as Einheit, zut_name as Zutat from zutat_einheit natural join (zutat, einheit) natural join zubereitung_einheit where zub_id=?';

            $zubArray = array($row[0]);
            $stmt1 = $con->GetStatement($query1, $zubArray);


            $con->PrintTable($stmt1);
        }
    } catch (Exception $e)
    {
        echo $e->getMessage();
    }

}else
{

    ?>
    <br>
    <h3>Nach Rezepten suchen</h3>
    <form method="post">
        <div class="table">
            <div class="tr">
                <div class="th">
                    <label for="suche">Rezeptnamen suchen (auch Wortteil möglich):</label>
                </div>
                <div class="td">
                    <input class="textonly" id="suche" type="text" name="rezname" placeholder="z.B. Schnitzel">
                </div>
            </div>
            <div class="tr">
                <div class="td">
                    <input type="submit" name="search" value="suchen">
                </div>
            </div>
        </div>
    </form>
    <?php
}
