<style>
    .center {
        margin: auto;
        width: 60%;
        border: 3px solid #2273A5;
        padding: 20px;
    }
</style>

<?php
$file = fopen('Top100.csv', 'r');
$all_rows = array();
$header = fgetcsv($file);
while ($row = fgetcsv($file)) {
    $all_rows[] = array_combine($header, $row);
}
fclose($file);
?>
<br>
<div class="center" style="text-align: center">

<table>
    <tr>
        <th>Rank</th>
        <th>Project Name</th>
        <th>Value(M)</th>
        <th>State</th>
        <th>Sector</th>
    </tr>

<?php
foreach ($all_rows as $rows => $row) {

    echo '<tr>';
    echo '<td>'.$row['Rank'].'</td>';
    echo '<td><a href="https://gvip.io/projects/'.$row['pid'].'">'.$row['Project Name'].'</a></td> ';
    echo '<td>'.$row['Value'].'</td>';
    echo '<td>'.$row['State'].'</td>';
    echo '<td>'.$row['Sector'].'</td>';
    echo '</tr>';

}
?>
</table>
</div>
<br>
