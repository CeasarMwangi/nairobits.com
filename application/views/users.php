<section ng-app="usersApp" ng-controller="usersController">

<div class="container">
<div class="row fix">
	<div class="col-md-12 text-center">
		<input type="text" ng-model="nameFilter" placeholder="Search..."/>
	</div>
</div><!-- .row -->
<div class="row topbar">
	<div class="col-md-12">
	</div>
</div><!-- .row -->
	<div class="row user" ng-repeat="user in users | filter: searchFilter ">

	    
	    <div class="col-md-2"> 
	        <img class="img-responsive img-thumbnail" ng-src="{{user.user_thumbnail}}">
	    </div>
	    <div class="col-md-8">
	        <h3>Name: {{user.full_name}}</h3>
	        <h3>Username: {{user.username}}</h3>
	        <h3>User Category: {{user.user_category}}</h3>
	        <h4>Phone Number: {{user.phone_number}}</h4>
	        <h5>Email Address: {{user.email_address}}</h5>
	        <h5>Current Center: {{user.center_name +" "+ user.location}}</h5>
	    </div>
	    <div class="col-md-2">
	        <a ng-href="user/edit/{{user.user_id}}" class="btn btn-primary edit-user">Manage</a>
	    </div>            
	</div>
</div><!-- .container -->
</section>
