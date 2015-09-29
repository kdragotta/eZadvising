<?php
$planCount = 2;

if($planCount > 1)
{
    echo('<div class="tab-content">');
}

for($i = 0; $i < $planCount; $i++) {
?>

<div id="plan<?php echo $i; ?>" class="tab-pane fade in active">

<tr>
    <!-- Plan Title Manipulation -->
    <td><h3 id="title <?php echo $i; ?>">Default Title <?php echo $i; ?></h3></td>
</tr>

<tr>
    <td>
        <button data-show="on" onclick="title_show()"> Change Plan Name</button>
        <button data-show="on" onclick="showHideSummers()"> Show/Hide Summers</button>
    </td>
</tr>
<!-- <tr> <td><button onclick="unplan()" > Save Plan </button> </td> </tr>
<tr> <td><button onclick="unplan()" > Revert to Saved Plan </button></td></tr>
-->
</table>
<div id="thePlan<?php echo $i; ?>"></div>
</div>

<?php } ?>