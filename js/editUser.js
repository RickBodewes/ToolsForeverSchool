function removeUser(userID){
    if(confirm('Weet je zeker dat je dit gebruikersaccount wilt opheffen?')){
        $.ajax({
            url: '/admin/ajax/removeUser.php',
            type: 'post',
            data: { 
                userID: userID
             },
            success: function (result) {
    
                if(JSON.parse(result).success){
                    location.reload();
                }
            }
        }); 
    }
}