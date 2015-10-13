<script>

    function getState(val) {
        $.ajax({
            type: "POST",
            url: "get_state.php",
            data:'dept='+val,
            success: function(data){
                $("#state-list").html(data);
            }
        });
    }

</script>