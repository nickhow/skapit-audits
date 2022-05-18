
function updateProgress(target){
        targetEl = document.getElementById(target); 
        
        var section_questions = targetEl.querySelectorAll('select, input');
        var complete = true;
        section_questions.forEach(function(element){
            if( element.value == "Unanswered" || element.value === "" ) {
                complete = false;
            }
        });
        if(complete){
            
            if(sections[target] == 'incomplete'){
                //newly completed -> so update the progress and hide the section
                sections[target] = 'complete';
                
                var bsCollapse = new bootstrap.Collapse(targetEl,{
                    toggle: false,
                }); 
                bsCollapse.hide();
                
                targetEl.previousElementSibling.firstElementChild.classList.add("completed");
                var progress = document.getElementById('progressBar');
                var now = parseInt(progress.getAttribute('aria-valuenow'));
                var newProgress = now+=10;
                progress.setAttribute('aria-valuenow', newProgress);
                progress.style.width = now + "%";
                progress.innerHTML = (newProgress)/10+"/10";
                
                offset = '85';
                window.scroll(0, targetEl.offsetTop - offset);
                
            } else { //re-opened and edited ... still needs closing.
               
                var bsCollapse = new bootstrap.Collapse(targetEl,{
                    toggle: false,
                }); 
                bsCollapse.hide();
                                
                if(target == "form-accordion-fire-body"){
                    offset = '85';
                    window.scroll(0, targetEl.offsetTop - offset);
                }
            }
            

        } else {
            if(sections[target] == 'complete'){
                //section has been uncompleted -> go back a step.
                
                var bsCollapse = new bootstrap.Collapse(targetEl,{
                    toggle: false,
                }); 
                bsCollapse.show(); //pin it open
                
                sections[target] = 'incomplete';
                targetEl.previousElementSibling.firstElementChild.classList.remove("completed");
                var progress = document.getElementById('progressBar');
                var now = parseInt(progress.getAttribute('aria-valuenow'));
                var newProgress = now-=10;
                progress.setAttribute('aria-valuenow', newProgress);
                progress.style.width = now + "%";
                progress.innerHTML = (newProgress)/10+"/10";    
            }
        }
    }

    
        const sectionbodies = document.querySelectorAll('.accordion-body');
        const sections = [];
        
        sectionbodies.forEach(function(element){
            var key = element.parentElement.id
            sections[key] = 'incomplete';
        });
        
        window.addEventListener('load',function(){
            for (const [key, value] of Object.entries(sections)) {
                updateProgress(key)
            }
        });
    
        //Question 24 hides Q25
        if( document.getElementById('Q24') !== null ){
            window.addEventListener('load',function(){updateForm('Q24','No',['Q25'] )});
            document.getElementById('Q24').addEventListener('change',function(){updateForm('Q24','No',['Q25'] )});
        }

        
        //Question 36 hides Q37
        if( document.getElementById('Q36') !== null ){
            window.addEventListener('load',function(){updateForm('Q36','No',['Q37'] )});
            document.getElementById('Q36').addEventListener('change',function(){updateForm('Q36','No',['Q37'] )});
        }
        
        //Question 70 hides Q71 .. Q85
        if( document.getElementById('Q70') !== null ){
            window.addEventListener('load',function(){updateForm('Q70','0',['Q71','Q72','Q73','Q74','Q75','Q76','Q77','Q78','Q79','Q80','Q81','Q82','Q83','Q84','Q85'] )});
            document.getElementById('Q70').addEventListener('change',function(){updateForm('Q70','0',['Q71','Q72','Q73','Q74','Q75','Q76','Q77','Q78','Q79','Q80','Q81','Q82','Q83','Q84','Q85'] )});
        }
        
        //Question 102 hides Q103 .. Q108
        if( document.getElementById('Q102') !== null ){
            window.addEventListener('load',function(){updateForm('Q102','No',['Q104','Q106'] )});
            document.getElementById('Q102').addEventListener('change',function(){updateForm('Q102','No',['Q104','Q106'] )});
        }
        
        //Question 111 hides Q112 .. Q114
        if( document.getElementById('Q111') !== null ){
            window.addEventListener('load',function(){updateForm('Q111','No',['Q112','Q113','Q114'] )});
            document.getElementById('Q111').addEventListener('change',function(){updateForm('Q111','No',['Q112','Q113','Q114'] )});
        }
        
        //take a question number and answer -> hide an array of questions
        function updateForm(trigger,answer,targets){
            var current_question_element = document.getElementById(trigger);
            var current_answer;

            //if type is input
            if(current_question_element.tagName =="INPUT"){
                current_answer = current_question_element.value;
            }
            //if type is select
            if(current_question_element.tagName =="SELECT"){
                current_answer = current_question_element.options[current_question_element.selectedIndex].getAttribute('data-response');
            }
            
            if(current_answer == answer){ //Hide questions and remove answers
            
                targets.filter( element => document.getElementById(element) !== null ).forEach(function(element){ //remove targets not in this form
                
                    var hiddenEl = document.getElementById(element).closest(".row, .my-3");  // get the question row - parent to all Q elements

                    var hiddenInput = hiddenEl.querySelectorAll('input');
                    hiddenInput.forEach(element => element.value="N/A");
                    
                    var hiddenSelect = hiddenEl.querySelectorAll('select');
                    hiddenSelect.forEach(function(element){
                       for (var i = 0; i < element.options.length; i++) {
                            var dr = element.options[i].getAttribute('data-response');

                            if(dr == "N/A") {

                                element.value = element.options[i].value;
                                element.options[i].selected = 'selected'; //select NA so we can fold the section when completed
            
                            }
                        }

                    });
                hiddenEl.style.display="none";       
                updateProgress(document.getElementById(element).closest(".accordion-collapse").id); //update the progress bar
            
                });
                
            } else {
                
                targets.filter( element => document.getElementById(element) !== null ).forEach(function(element){ //Show questions and mark as unanswered
                    var hiddenEl = document.getElementById(element).closest(".row, .my-3");
                    hiddenEl.style.display="block";
                    
                    var hiddenEls = hiddenEl.querySelectorAll('select, input');
                 
                        hiddenEls.forEach(function(element){
                            
                            if(!isLocked){ // only clean the answers to the unhidden questions if the form is still being completed
                            
                                if(document.getElementById("A"+element.value) !== null){
                                    if(document.getElementById("A"+element.value) == "131"){
                                        // skip this one, N/A is used differently
                                        element.value = "131";
                                    } else if(document.getElementById("A"+element.value).getAttribute('data-response') == "N/A"){
                                        element.value="Unanswered";
                                    }
                                }
                            }
                            
                        });
       
                    updateProgress(document.getElementById(element).closest(".accordion-collapse").id); //update the progress bar
                });
            }
        }
        
        function deleteFile(file_name){
            $.ajax({
                url: 'https://audit.ski-api-technologies.com/remove-file/'+file_name,
                type: 'POST',
                data: {
                
                },
                success: function(msg) {
                    console.log(msg);
                    document.getElementById(file_name).remove();
                    
                }               
            });
      }
        
        function formValidation(){
        
        sectionbodies.forEach(function(section){
            var questions = section.querySelectorAll('input, select');
            questions.forEach(function(question){
                if(question.value == "" || question.value == "Unanswered") { 
                    question.style.backgroundColor="rgb(255 0 0 / 21%)";

                    //add event listener to remove bg color on change
                    question.addEventListener('change',function(question){
                        var current_question = question.target;
                        if(current_question.value == "" || current_question.value == "Unanswered") { 
                            current_question.style.backgroundColor="rgb(255 0 0 / 21%)";
                        } else {
                            current_question.style.backgroundColor="#fff";
                        }
                    });
                }  
            });
        });
    }
        
        
