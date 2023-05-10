<?php 

/**
 * Handles the logic of loading the page step by step
 */
function loadScripts(){
    ?>
            <script>


                // Decreases value of ingredients by 1 when - button is clicked
                $(document).on("click", ".item-sell-step-3", function(e){
                    let curr_val = parseInt($(this).next().val());
                    curr_val -= 1;
                    $(this).next().val(curr_val);
                });

                // Increases value of ingredients by 1 when + button is clicked
                $(document).on("click", ".item-buy-step-3", function(e){
                    let curr_val = parseInt($(this).prev().val());
                    curr_val += 1;
                    $(this).prev().val(curr_val);
                });

                $(document).on("click", ".item-sell-step-5", function(e){
                    let curr_val = parseInt($(this).next().val());
                    let price_per = parseFloat($(this).next().attr("price"));
                    curr_val -= 1;
                    $(this).next().val(curr_val);
                    $(this).next().next().next().text(curr_val * price_per);
                });

                // Increases value of ingredients by 1 when + button is clicked
                $(document).on("click", ".item-buy-step-5", function(e){
                    let curr_val = parseInt($(this).prev().val());
                    let price_per = parseFloat($(this).prev().attr("price"));
                    curr_val += 1;
                    $(this).prev().val(curr_val);
                    $(this).next().text(curr_val * price_per);
                });

                $(document).on("change", ".item-input-step-5", function(e){
                    let num_items = parseFloat($(this).val());
                    let price_per = parseFloat($(this).attr("price"));
                    $(this).next().next().text(num_items * price_per);
                });
            
            </script>

            <script> 
                function load_budget_ajx(){
                    $.ajax({
                        type: "POST",
                        url: "Views/browse-recipes-page-view.php",
                        data: {function_name: "populate_budget_page"},
                        success: function(data){
                            $("#step-1").html(data);
                        }

                    });
                }       
        
            </script>

            <script> 
                function load_recipes_ajx(){
                    $.ajax({
                        type: "POST",
                        url: "../BackEnd/Controllers/recipe-controller.php",
						data: {action: "Fetch_Recepies"},
						success: function(data){
							$.ajax({
								type: "POST",
								url: "Views/browse-recipes-page-view.php",
								data: {function_name : "populate_Recipes", recipes: data},
								success:function(data){
                                    $("#step-1").html("");
									$("#step-2").html(data);
                                    
								}
							});
						}

                    });
                }       
        
            </script>

            <script> 
                function load_ingredients_ajx(recipe){
                    $.ajax({
                        type: "POST",
                        url: "../BackEnd/Controllers/ingredient-controller.php",
                        data: {action: "Fetch_Ingredients", "recipe_id": recipe},
                        success: function(data){
                            ingredients = data;
                            let sending = {function_name : "populate_Ingredients", "ingredients": ingredients};
                            $.ajax({
                                type: "POST",
                                url: "Views/browse-recipes-page-view.php",
                                data: sending,
                                success:function(data){
                                    $("#step-1").html("");
                                    $("#step-2").html("");
                                    $("#step-3").html(data);
                                }
                            });
                        }

                    });
                }       
        
            </script>

            <script>
                function return_to_ingredients_ajx(ings){
                    $.ajax({
                        type: "POST",
                        url: "Views/browse-recipes-page-view.php",
                        data: {function_name : "populate_Ingredients", "ingredients": ings},
                        success:function(data){
                            $("#step-4").html("");
                            $("#step-3").html(data);
                        }
                    });
                }
            </script>

            <script>
                function load_supermarkets_ajx(ing_IDs, ing_quantity) {
                return new Promise((resolve, reject) => {
                    $.ajax({
                    type: "POST",
                    url: "../BackEnd/Controllers/supermarket-controller.php",
                    data: {
                        action: "Fetch_SuperMarkets_With_Prices",
                        ing_IDs: ing_IDs,
                        ing_quantity: ing_quantity
                    },
                    success: function(data) {
                        let markets = data;
                        let response = JSON.parse(data);
                        response = ingredients_of_supermarket["supermarketIngredients"];
                        response = JSON.stringify(data);
                        $.ajax({
                        type: "POST",
                        url: "Views/browse-recipes-page-view.php",
                        data: { function_name: "populate_market_page", markets: markets },
                        success: function(data) {
                            $("#step-3").html("");
                            $("#step-4").html(data);
                            resolve(response);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            reject(errorThrown);
                        }
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        reject(errorThrown);
                    }
                    });
                });
                }

            </script>

            <script>
                function show_buget_insufficient_page_ajx(price, budg){
                    $.ajax({
                        type: "POST",
                        url: "Views/browse-recipes-page-view.php",
                        data: {function_name : "populate_budget_insuficient_page", "budget": budg, "price": price},
                        success: function(data){
                            $("#step-4-half").html(data);
                            $("#step-4").html("");
                        }
                    });
                }
            </script>


            <script>
                function load_step_5_ajx(totalPrice, supermar, ings, ingredients_of_supermar){
                    $.ajax({
                        type: "POST",
                        url: "Views/browse-recipes-page-view.php",
                        data: {function_name : "populate_step_5_page", "supermarket": supermar, "ingredients" : ings, "ingredients_of_supermarket": ingredients_of_supermar, "totalPrice": totalPrice},
                        success: function(data){
                            $("#step-4-half").html("");
                            $("#step-4").html("");
                            $("#step-5").html(data);
                        }
                    });
                }
            </script>

            




            <script>  
                let budget = 0;
                let recipe_id = -1;
                let ingredients = null;
                let ingredient_ids = [];
                let ingredient_quantity = [];
                let supermarket = null;
                let chosen_price = -1;
                let ingredients_of_supermarket = [];

                $(document).ready(function(){
                    // Loads step-1 page
                    load_budget_ajx();
                });

                // Load step-1 when back button is clicked in step-2
                $(document).on("click", "#back-button-step-2", function(e){
                    e.preventDefault();
                    buget = 0;
                    document.getElementById("step-2").innerHTML = "";
                    load_budget_ajx();
                });

                // loads step-2 when budget is submitted
                $(document).on("submit", "#budget-form", function(e){
                    e.preventDefault();
                    budget = $("#budget").val();
                    if(recipe_id != -1){
                        load_ingredients_ajx(recipe_id);
                    }
                    else{
                        load_recipes_ajx();
                    }
                });

                

                // Load step-2 when back button is clicked in step-3
                $(document).on("click", "#back-button-step-3", function(e){
                    e.preventDefault();
                    recipe_id = -1;
                    document.getElementById("step-3").innerHTML = "";
                    load_recipes_ajx();
                    
                });


                // Loads step-3 when recipe is clicked
                $(document).on("click", ".recipe", function(e){
                    e.preventDefault();
                    recipe_id = $(this).attr('recipe_id');
                    load_ingredients_ajx(recipe_id);

                });

                // Loads step-3 when back button is clicked in step-4
                $(document).on("click", "#back-button-step-4", function(e){
                    e.preventDefault();
                    ingredients =JSON.parse(ingredients);
                    
                    for(let i=0; i<ingredient_quantity.length; i++){
                        ingredients[i]["Quantity"] =ingredient_quantity[i];
                    }
                    ingredients =JSON.stringify(ingredients);
                    ingredient_ids = [];
                    ingredient_quantity = [];
                    return_to_ingredients_ajx(ingredients);
                });


                // Loads step 4 when Next button is clicked
                $(document).on("click", "#next-button-step-3", function(e){
                    e.preventDefault();
                    let ingredients_temp = JSON.parse(ingredients);
                    
                    
                    let values = document.querySelectorAll(".item-wrapper .item-input-step-3");
                    for (let i = 0; i < values.length; i++) {
                        ingredient_quantity.push(values[i].value);
                        ingredient_ids.push(ingredients_temp[i]["4"]);
                    }

                    ingredients =JSON.parse(ingredients);
                    
                    for(let i=0; i<ingredient_quantity.length; i++){
                        ingredients[i]["Quantity"] = ingredient_quantity[i];
                    }
                    
                    ingredients =JSON.stringify(ingredients);
                    
                    load_supermarkets_ajx(ingredient_ids, ingredient_quantity).then((response) => {
                        ingredients_of_supermarket = response;
                    }).catch((error) => {
                        console.error(error);
                    });
                });

                // Loads step 5 if the buget is sufficient else goes to step 4 and a half
                $(document).on("click", ".select-supermarket-button", function(e){
                    e.preventDefault();
                    supermarket = $(this).prev().prev().html();
                    supermarket = $("#supermarket-select").val();
                    chosen_price = $("#supermarket-select option:selected").attr("price");
                    ingredients_of_supermarket = JSON.parse(ingredients_of_supermarket);
                    
                    if(budget >= parseInt(chosen_price)){
                        let ingredients_of_supermarket_param = JSON.parse(ingredients_of_supermarket);
                        ingredients_of_supermarket_param =ingredients_of_supermarket_param["supermarketIngredients"];
                        ingredients_of_supermarket_param = JSON.stringify(ingredients_of_supermarket_param);
                        load_step_5_ajx(chosen_price, supermarket, ingredients, ingredients_of_supermarket_param);
                    }
                    else{
                        show_buget_insufficient_page_ajx(chosen_price, budget);
                    }

                });

                // Loads step-5 after continue button is pressed
                $(document).on("click", "#continue-step-4-half", function(){
                    let ingredients_of_supermarket_param = JSON.parse(ingredients_of_supermarket);
                    ingredients_of_supermarket_param =ingredients_of_supermarket_param["supermarketIngredients"];
                    ingredients_of_supermarket_param = JSON.stringify(ingredients_of_supermarket_param);
                    load_step_5_ajx(chosen_price, supermarket, ingredients, ingredients_of_supermarket_param);
                });


                // Returns all the way back to step-1 after the return button is pressed in step-4 and a half
                $(document).on("click", "#return-step-4-half", function(){
                    budget = 0;
                    ingredients = null;
                    ingredient_ids = [];
                    ingredient_quantity = [];
                    supermarket = null;
                    recipe_id = -1;
                    chosen_price = -1;
                    $("#step-4-half").html("");
                    load_budget_ajx();
                });

                $(document).on("click", "#back-button-step-5", function(e){
                    e.preventDefault();
                    ingredients =JSON.parse(ingredients);
                    
                    for(let i=0; i<ingredient_quantity.length; i++){
                        ingredients[i]["Quantity"] = ingredient_quantity[i];
                    }
                    
                    ingredients =JSON.stringify(ingredients);
                    supermarket = null;
                    document.getElementById("step-5").innerHTML = "";
                    load_supermarkets_ajx(ingredient_ids, ingredient_quantity);
                });
 
            </script>

            
    <?php
}



/**
 * Loads step-1 or the budget form page
 */
function populate_budget_page(){
    ?>
    <div class="block">
            <a href="#"><div class="back">Back</a>
        </div>
        <div class="container">
            <h1>Enter your budget</h1>
            <form id="budget-form">
                <input type="text" name="budget" id="budget" placeholder="e.g: 100$" class="textfield" >
                <br><br>
                <button type="submit" class="mbutton">Submit</button>
            </form>
    </div>
    <?php
}
/**
 * Loads step-2 or the recipe view page
 */
function populate_Recipes($recipes){
    $recipes = json_decode($recipes);
    for($i = 0; $i < sizeof($recipes); $i++){
        $image = "../" . $recipes[$i]->Image;
        $recipe_name = $recipes[$i]->Recipe_Name;
        $recipe_id = $recipes[$i] ->Recipe_ID;
        ?>

        <div class="recipe" recipe_id = "<?php echo $recipe_id?>"> 
            <image width=100 class="recipe-img" src="<?php echo $image?>">
            <span> <?php echo $recipe_name ?></span>
        </div>

    <?php
    }
    ?>
    <button id="back-button-step-2"> Back </button>
    <?php
}


/**
 * Loads step-3 or the ingredients page
 */
function populate_Ingredients($ingredients){
    $ingredients = json_decode($ingredients);
    for($i = 0; $i < sizeof($ingredients); $i++){
        ?>
        <div class='item-wrapper'>
            <img width=100 class='item-img' src= <?php echo "../" . $ingredients[$i]->Image ?> >
            <div class='item-name'> <?php echo $ingredients[$i]->Ingredient_Name ?></div>
            <div class='item-cost'>
                <button class='item-sell-step-3'>-</button>
                <input class='item-input-step-3' type='number' pattern='\d*' value= <?php echo $ingredients[$i]->Quantity ?> >
                <button class='item-buy-step-3'>+</button>
                <?php echo $ingredients[$i]->Unit ?>
            </div>
            </div>
        <?php
    } 

    ?>
    <button id="back-button-step-3"> Back </button>
    <button id="next-button-step-3"> Next </button>
    <?php
}


/**
 * Loads step-4 or the SuperMarkets page
 */
function populate_Markets($markets){
    $markets = json_decode($markets, true);
    $supermarketPrices = $markets["supermarketPrices"];
    $supermarketContaining = $markets["supermarketContaining"];
    $ingredients = $markets["ingredients"];
    // Display the supermarket name and the total price for each supermarket
    foreach ($supermarketPrices as $supermarketName => $totalPrice) {
        if ($supermarketContaining[$supermarketName]== sizeof($ingredients)){
            echo "Supermarket Name: $supermarketName, Total Price: $totalPrice <br>";
        }
        
    }
    $availableSupermarkets = array();
    $availableSupermarketsPrices = array();
    foreach ($supermarketContaining as $supermarketName => $numIngredients) {
        if ($numIngredients == sizeof($ingredients)) {
            $availableSupermarkets[] = $supermarketName;
            $availableSupermarketsPrices[] = $supermarketPrices[$supermarketName];
        }
    }
    

// Check if any supermarkets are available
    if (count($availableSupermarkets) == 0) {
        echo "No supermarkets found that contain all ingredients.";
    } else {
        // Display the available supermarkets in a dropdown menu
        echo "<form method='post' action=''>";
        echo "<label for='supermarket-select'>Choose a supermarket:</label>";
        echo "<select name='supermarket' id='supermarket-select'>";
        for ($i=0; $i<sizeof($availableSupermarkets); $i++) {
            echo "<option price='$availableSupermarketsPrices[$i] 'value='$availableSupermarkets[$i]'>$availableSupermarkets[$i]</option>";
        }
        echo "</select>";
        echo "<input class='select-supermarket-button' type='button' value='Select'>";
        echo "</form>";

        // If the user has selected a supermarket, display its details
        if (isset($_POST['supermarket'])) {
            $selectedSupermarket = $_POST['supermarket'];
            echo "Selected supermarket: $selectedSupermarket<br>";

            // Display the details of the selected supermarket
            foreach ($supermarketPrices as $supermarketName => $totalPrice) {
                if ($supermarketName == $selectedSupermarket) {
                    echo "Supermarket Name: $supermarketName, Total Price: $totalPrice <br>";
                }
            }
        }
    }
    ?>
    <button id="back-button-step-4"> Back </button>
    <?php

}


function populate_step_5_page($totalPrice, $supermarket, $ingredients, $ingredients_of_supermarket){
    $ingredients = json_decode($ingredients);
    $ingredients_of_supermarket = json_decode($ingredients_of_supermarket);
    echo "<h1> SuperMarket: $supermarket </h1>";
    echo "<span>TOTAL PRICE: $totalPrice</span>";

    for($i = 0; $i < sizeof($ingredients); $i++){
        $ing_price = $ingredients_of_supermarket->{$supermarket}->{$ingredients[$i]->Ingredient_ID}[0];
        $ing_quantity = $ingredients_of_supermarket->{$supermarket}->{$ingredients[$i]->Ingredient_ID}[1];

        $price = $ing_price * $ing_quantity;
        ?>
        <div class='item-wrapper'>
            <img width=100 class='item-img' src= <?php echo "../" . $ingredients[$i]->Image ?> >
            <div class='item-name'> <?php echo $ingredients[$i]->Ingredient_Name ?></div>
            <div class='item-cost'>
                <button class='item-sell-step-5'>-</button>
                <input price="<?php echo $ing_price ?>"class='item-input-step-5' type='number' pattern='\d*' value= <?php echo $ingredients[$i]->Quantity ?> >
                <button class='item-buy-step-5'>+</button>
                <?php echo $ingredients[$i]->Unit ?>
                <?php echo "<span> $price </span>" ?>
            </div>
            </div>
        <?php
    } 

    ?>
    <button id="back-button-step-5"> Back </button>
    <?php
}

function populate_budget_insuficient_page($budget, $price){
    $diff = floatval($price) - floatval($budget);
   ?>
    <div>
        <span> Unfortunately, your budget is not enough for this recipe. <br>
               Your buget was <?php echo $budget ?>$, the price is <?php echo $price ?>$ <br>
                You are <?php echo $diff ?> $ short. <br>
                Do you wish to continue or go back to step-1?
        </span>
        <button id="continue-step-4-half"> CONTINUE </button>
        <button id="return-step-4-half"> RETURN </button>
    </div>
   <?php
}





// Controls the page loading according to the name of the function called
if(isset($_POST["function_name"])){
    $name = $_POST["function_name"];
    switch($name){
        case "populate_budget_page":
                
            echo populate_budget_page();
                
            break;
        case "populate_Recipes":
            if(isset($_POST["recipes"])){
                echo populate_Recipes($_POST["recipes"]);
            }
            break;
        case "populate_Ingredients":
            if(isset($_POST["ingredients"])){
                echo populate_Ingredients($_POST["ingredients"]);
            }
            break;
    
        case "populate_market_page":
            if(isset($_POST["markets"])){

                echo populate_Markets($_POST["markets"]);
            }
            break;

        case "populate_budget_insuficient_page":
            if(isset($_POST["budget"]) && isset($_POST["price"])){

                echo populate_budget_insuficient_page($_POST["budget"], $_POST["price"]);
            }
            break;

        case "populate_step_5_page":
            if(isset($_POST["totalPrice"]) && isset($_POST["ingredients"]) && isset($_POST["supermarket"]) && isset($_POST["ingredients_of_supermarket"])){
                echo populate_step_5_page($_POST["totalPrice"], $_POST["supermarket"], $_POST["ingredients"], $_POST["ingredients_of_supermarket"]);
            }
            
            break;

    }
}


?>
