const vSignup= new JustValidate("#signup"); 
      vSignup
        .addField("#i-nome",[
            {
                rule: "required"
            }
        ])
        .addField("#i-cognome",[
            {
                rule: "required"
            }
        ])
        .addField("#i-email",[
            {
                rule: "required"
            },
            {
                rule: "email"
            }
            /*{
                validator: (value) => () => {
                    return fetch("validazioneEmail.php?email=" + encodeURIComponent(value))
                    .then(function(response){
                        file = response.json();
                    })
                    .then(function(file){
                        return file.available;
                    });
                },
                errorMessage: "email already taken",
            }*/
        ])
        .addField("#i-pass",[
            {
                rule: "required"
            },
            {
                rule: "password"
            }
        ])
        .addField("#i-pass2",[
            {
                validator: (value, fields)=>{
                    return value === fields["#i-pass"].elem.value;
                },
                errorMessage: "Password should match"
            }
        ])
        .onSuccess((event) => {
            document.getElementById("signup").submit();
            
        });
        
