
// remove the error class on answer update
var question_selects = document.querySelectorAll("select");
question_selects.forEach(function(el){
    el.addEventListener("change", function(){
        el.parentElement.parentElement.classList.remove("error_question");
       el.onchange();
    });
});

function updateProgress(target){
        targetEl = document.getElementById(target); 
      
        //count the errors and hold the accordion open while there are some ... need to remove error_question class on update answer.
        var error_count = targetEl.querySelectorAll(".error_question");
        if(error_count.length > 0) {
            // stop the rest of the process to hold the accordion open ...
           
            return;
        }

        var section_questions = targetEl.querySelectorAll('select, input');
        var complete = true;
        section_questions.forEach(function(element){
            if( element.value == "Unanswered" || element.value === "" ) {
                complete = false;
                console.log("Section not complete because "+element.id);
                console.log("element value: "+element.value);
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
                console.log("section closed");
                if(isLocked){
                    targetEl.previousElementSibling.firstElementChild.classList.add("completed");
                } else {
                    targetEl.previousElementSibling.firstElementChild.classList.add("completed-pending");
                }
                
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
                console.log("section closed");                
                if(target == "form-accordion-fire-body"){
                    offset = '85';
                    window.scroll(0, targetEl.offsetTop - offset);
                }
            }
            

        } else {
            if(sections[target] == 'complete'){
                //section has been uncompleted -> go back a step.
                console.log("Uncompleted ... ");
                var bsCollapse = new bootstrap.Collapse(targetEl,{
                    toggle: false,
                }); 
                bsCollapse.show(); //pin it open
                console.log("section openned");
                sections[target] = 'incomplete';
                targetEl.previousElementSibling.firstElementChild.classList.remove("completed,completed-pending");
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
                updateProgress(key);
            }
            console.log("FINISHED WINDOW LOAD FUNCTION");
        });

        //Question 10 hides Q11 **NEW**
        if( document.getElementById('Q10') !== null ){
            window.addEventListener('load',function(){updateForm('Q10','No',['Q11'] );});
            document.getElementById('Q10').addEventListener('change',function(){updateForm('Q10','No',['Q11'] );});
        }

        //Question 24 hides Q25
        if( document.getElementById('Q24') !== null ){
            window.addEventListener('load',function(){updateForm('Q24','No',['Q25'] );});
            document.getElementById('Q24').addEventListener('change',function(){updateForm('Q24','No',['Q25'] );});
        }

        //Question 33 hides Q34, Q35 **NEW**
        if( document.getElementById('Q33') !== null ){
            window.addEventListener('load',function(){updateForm('Q33','No',['Q34','Q35'] );});
            document.getElementById('Q33').addEventListener('change',function(){updateForm('Q33','No',['Q34','Q35'] );});
        }
        
        //Question 36 hides Q37
        if( document.getElementById('Q36') !== null ){
            window.addEventListener('load',function(){updateForm('Q36','No',['Q37'] );});
            document.getElementById('Q36').addEventListener('change',function(){updateForm('Q36','No',['Q37'] );});
        }

        //Question 62 hides Q64, Q66, Q68, Q69 **NEW**
        if( document.getElementById('Q62') !== null ){
            window.addEventListener('load',function(){updateForm('Q62','No',['Q64','Q66','Q68','Q69'] );});
            document.getElementById('Q62').addEventListener('change',function(){updateForm('Q62','No',['Q64','Q66','Q68','Q69'] );});
        }
        
        //Question 70 hides Q71 .. Q85 & NEW 135, 136
        if( document.getElementById('Q70') !== null ){
            window.addEventListener('load',function(){updateForm('Q70','0',['Q71','Q72','Q73','Q74','Q75','Q76','Q77','Q78','Q79','Q80','Q81','Q82','Q83','Q84','Q85','Q135','Q136','138'] );});
            document.getElementById('Q70').addEventListener('change',function(){updateForm('Q70','0',['Q71','Q72','Q73','Q74','Q75','Q76','Q77','Q78','Q79','Q80','Q81','Q82','Q83','Q84','Q85','Q135','Q136','138'] );});
        }

        //Question 88 hides Q89, Q90 **NEW**
        if( document.getElementById('Q88') !== null ){
            window.addEventListener('load',function(){updateForm('Q88','No',['Q89','Q90'] );});
            document.getElementById('Q88').addEventListener('change',function(){updateForm('Q88','No',['Q89','Q90'] );});
        }

        //Question 93 hides Q94, Q95 **NEW**
        if( document.getElementById('Q93') !== null ){
            window.addEventListener('load',function(){updateForm('Q93','No',['Q94','Q95'] );});
            document.getElementById('Q93').addEventListener('change',function(){updateForm('Q93','No',['Q94','Q95'] );});
        }

        //Question 96 hides Q97, Q98 **NEW**
        if( document.getElementById('Q96') !== null ){
            window.addEventListener('load',function(){updateForm('Q96','No',['Q97','Q98'] );});
            document.getElementById('Q96').addEventListener('change',function(){updateForm('Q96','No',['Q97','Q98'] );});
        }
        
        //Question 99 hides Q100 **NEW**
        if( document.getElementById('Q99') !== null ){
            window.addEventListener('load',function(){updateForm('Q99','No',['Q100'] );});
            document.getElementById('Q99').addEventListener('change',function(){updateForm('Q99','No',['Q100'] );});
        }

        //Question 102 hides Q103 .. Q108 **Newly extended - to what is (and was) in the comment ... ?? Concerned the NA will break on 103 + 105.
        if( document.getElementById('Q102') !== null ){
            window.addEventListener('load',function(){updateForm('Q102','No',['Q103','Q104','Q105','Q106','Q107','Q108'] );});
            document.getElementById('Q102').addEventListener('change',function(){updateForm('Q102','No',['Q103','Q104','Q105','Q106','Q107','Q108'] );});
        }
        
        //Question 111 hides Q112 .. Q114
        if( document.getElementById('Q111') !== null ){
            window.addEventListener('load',function(){updateForm('Q111','No',['Q112','Q113','Q114'] );});
            document.getElementById('Q111').addEventListener('change',function(){updateForm('Q111','No',['Q112','Q113','Q114'] );});
        }

        //Question 126 hides Q127, Q128 **NEW**
        if( document.getElementById('Q126') !== null ){
            window.addEventListener('load',function(){updateForm('Q126','No',['Q127','Q128'] );});
            document.getElementById('Q126').addEventListener('change',function(){updateForm('Q126','No',['Q127','Q128'] );});
        }

        //Question 134 hides Q59, Q60 **NEW**
        if( document.getElementById('Q134') !== null ){
            window.addEventListener('load',function(){updateForm('Q134','No',['Q59','Q60'] );});
            document.getElementById('Q134').addEventListener('change',function(){updateForm('Q134','No',['Q59','Q60'] );});
        }  

        //Question 136 hides Q74, Q75 **NEW**
        if( document.getElementById('Q136') !== null ){
            window.addEventListener('load',function(){updateForm('Q136',['No','N/A'],['Q74','Q75'] ); });
            document.getElementById('Q136').addEventListener('change',function(){updateForm('Q136',['No','N/A'],['Q74','Q75'] );});
        }
        //Question 74 hides Q75 **NEW**
        if( document.getElementById('Q74') !== null ){
            window.addEventListener('load',function(){updateForm('Q74',['No','N/A'],['Q75'] );});
            document.getElementById('Q74').addEventListener('change',function(){updateForm('Q74',['No','N/A'],['Q75'] );});
        }      

        //Question 137 shows Q86, Q87 **NEW**
        if( document.getElementById('Q137') !== null ){
            window.addEventListener('load',function(){updateFormShow('Q137','Yes',['Q86','Q87'] );});
            document.getElementById('Q137').addEventListener('change',function(){updateFormShow('Q137','Yes',['Q86','Q87'] );});
        }

        //Question 107 shows 130
        if( document.getElementById('Q107') !== null ){
            window.addEventListener('load',function(){updateFormShow('Q107','Yes',['Q130'] );});
            document.getElementById('Q107').addEventListener('change',function(){updateFormShow('Q107','Yes',['Q130'] );});
        }

        //Question 131 shows 132
        if( document.getElementById('Q131') !== null ){
            window.addEventListener('load',function(){updateFormShow('Q131','Yes',['Q132'] );});
            document.getElementById('Q131').addEventListener('change',function(){updateFormShow('Q131','Yes',['Q132'] );});
        }


        //TEST THIS - I DOUBT BOTH WORK -- and where is the if no - is the depth displayed q?

        //new function to perfom some logic first, use map  -- almost there, not hiding Yes Q on change to No and vice versa. NA or Unanswered clears efficiently.

        //Question 135 shows 72 and 78 (which can later show 79) or 138 **NEW**
        if( document.getElementById('Q135') !== null ){
            window.addEventListener('load',function(){
                
                var map = new Map();
                map.set('Yes', ['Q72','Q78','Q79']);
                map.set('No', ['Q138']);
                updateFormShowLogical('Q135',map);
             //   updateFormShow('Q135','Yes',['Q72'] );
             //   updateFormShow('Q135','No',['Q138'] );
            });
            document.getElementById('Q135').addEventListener('change',function(){
                var map = new Map();
                map.set('Yes', ['Q72','Q78','Q79']);
                map.set('No', ['Q138']);
                updateFormShowLogical('Q135',map);
                updateProgress('form-accordion-pool-body');
            //    updateFormShow('Q135','Yes',['Q72'] );
            //    updateFormShow('Q135','No',['Q138'] );
            });
        }

        //Question 78 hide Q79 **NEW**
        if( document.getElementById('Q78') !== null ){
            window.addEventListener('load',function(){updateForm('Q78',['No','N/A'],['Q79'] );});
            document.getElementById('Q78').addEventListener('change',function(){updateForm('Q78',['No','N/A'],['Q79'] );});

            updateProgress('form-accordion-pool-body');

        }        




        //add logical function, trigger, map ( key value array [answer => [targets], ..  ] )
        function updateFormShowLogical(trigger, map){

            for (let [key, value] of map) {
                updateFormShow(trigger,key,value);
            }

        /*
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

            //now check the map for the answer as a key, if exists - run the show function based on the targets in the map

            var targets;

            //if the key exists
            if(map.has(current_answer)){

                //get the targets
                targets = map.get(current_answer);

                //push to the show function which will show the new question, but not hide any opened questions as not included in targets like normal functions do.
                updateFormShow(trigger,current_answer,targets);

            } else {

                //if not a key, then we need to do somthing to default hide the Qs
                //I need to deliver the trigger Q, the target ans (which is not the current answer), and the targets.

                for (let [key, value] of map) {

                    updateFormShow(trigger,key,value);
                }

            }
        */

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
            
            var proceed = false;

            //check if we have an array or single value, then check if the current answer is one we need to act on.
            if(Array.isArray(answer)){
                if(answer.includes(current_answer)) {
                    proceed = true;

                    if(trigger == "Q78"){
                        console.log(current_answer+" is in my answer array" );
                    }
                } else {
                    console.log(current_answer+" is not my answer array" );
                }
            } else {
                if(current_answer == answer){
                    proceed = true;
                }
            }
            
            if(proceed){ //Hide questions and remove answers
            

                targets.filter( element => document.getElementById(element) !== null ).forEach(function(element){ //remove targets not in this form
                
                    var hiddenEl = document.getElementById(element).closest(".row, .my-3");  // get the question row - parent to all Q elements

                    var hiddenInput = hiddenEl.querySelectorAll('input');
                    hiddenInput.forEach(function(){
                        
                        //treat 103, 105 & 108 differently, they should be 0 not N/A
                        if(element == 'Q103' || element == 'Q105'|| element == 'Q108' ){ 
                            document.getElementById(element).value="0";
                        } else {
                            element => element.value="N/A";
                            if(trigger == "Q78"){
                                console.log(element+" is set to N/A");
                            }
                        }
                    });

                    var hiddenSelect = hiddenEl.querySelectorAll('select');
                    hiddenSelect.forEach(function(element){
                       for (var i = 0; i < element.options.length; i++) {
                            var dr = element.options[i].getAttribute('data-response');

                            if(dr !== null){ //check not null before proceeding.

                                if(dr == "N/A" || dr.startsWith("N/A")) {  //extended to include starts with as Q34 hase data-response 'N/A  or not longer than 10 mts'

                                    element.value = element.options[i].value;
                                    element.options[i].selected = 'selected'; //select NA so we can fold the section when completed

                                    if(trigger == "Q78"){
                                        console.log(element+" is set to N/A");
                                    }
                                } 

                            }

                        }

                    });
                hiddenEl.style.display="none";    
                console.log("progress update... after element.id: " + element);
                
                if(trigger == "Q78"){
                    console.log("going into update... nearest accordion-collapse: "+document.getElementById(element).closest(".accordion-collapse").id);
                }
                updateProgress(document.getElementById(element).closest(".accordion-collapse").id); //update the progress bar
                

                });
                
            } else {// not interested in hiding it, make sure it is showing and reset answers

                targets.filter( element => document.getElementById(element) !== null ).forEach(function(element){ //Show questions and mark as unanswered
                    
                    var hiddenEl = document.getElementById(element).closest(".row, .my-3");
                    hiddenEl.style.display="block";
                    
                    var hiddenEls = hiddenEl.querySelectorAll('select, input');
                 
                        hiddenEls.forEach(function(element){
                            
                            if(!isLocked){ // only clean the answers to the unhidden questions if the form is still being completed

                                //First check for these specific questions
                                if((element.id == "Q103" || element.id == "Q105" || element.id == "Q108") && element.value == 0){ //check if it's 0 input value on these questions
                                    document.getElementById(element.id).value = "";

                                } else if(document.getElementById("A"+element.value) !== null){ //Otherwise, normal behaviour - check the answer, value here is that of selected select-option. 
                                    
                                    if(element.value == "131"){ //answer id of 131 (Q37 N/a)
                                        // skip this one, N/A is used differently

                                    } else if(document.getElementById("A"+element.value).getAttribute('data-response') == "N/A"){
                                        element.value="Unanswered";
                                        
                                    } 
                                } 
                            } 
                            
                        });
                        console.log("progress update... after element.id: " + element);
                   updateProgress(document.getElementById(element).closest(".accordion-collapse").id); //update the progress bar
                });
                //end of filtered loop of hide/show - now try updateProgress ...
            }
        }



                //take a question number and answer -> show an array of questions
                function updateFormShow(trigger,answer,targets){
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
//OLD LOGIC
               //     if(current_answer !== answer){ //Hide questions and remove answers
                


// NEW LOGIC
                    var proceed = false;

                    //check if we have an array or single value, then check if the current answer is one we need to act on.
                    if(Array.isArray(answer)){
                        if(!answer.includes(current_answer)) {
                            proceed = true;
                        }
                    } else {
                        if(current_answer !== answer){
                            proceed = true;
                        }
                    }



                    if(proceed){ //Hide questions and remove answers
//END NEW LOGIC

                        targets.filter( element => document.getElementById(element) !== null ).forEach(function(element){ //remove targets not in this form

                            var hiddenEl = document.getElementById(element).closest(".row, .my-3");  // get the question row - parent to all Q elements
        
                            var hiddenInput = hiddenEl.querySelectorAll('input');
                            hiddenInput.forEach(element => element.value="N/A");

                            //treat 103 and 105 and 108 differently
                            if(element.id == 'Q103' || element.id == 'Q105' || element.id == 'Q108'){
                                document.getElementById(element.id).value = "";
                            }

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
                        console.log("progress update... after element.id: " + element);  
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
                                            
                                            if(element.value == "131"){ 
                                                // skip this one, N/A is used differently
                                                
                                            } else if(element.id == "Q103" || element.id == "Q105" || element.id == "Q108" ){
                                                document.getElementById(element.id).value = "";

                                            } else if(document.getElementById("A"+element.value).getAttribute('data-response') == "N/A"){
                                                element.value="Unanswered";
                                               
                                            }
                                        }
                                    }
                                    
                                });
                            console.log("progress update... after element.id: " + element);
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
        
        
