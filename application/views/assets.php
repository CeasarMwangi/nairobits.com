<section ng-app="assetsApp" ng-controller="assetsController">
<div class="row fix">
	<div class="col-md-12 text-center">
		<input type="text" ng-model="assetFilter" placeholder="Search..."/>
	</div>
</div><!-- .row -->
<div class="row topbar">
	<div class="col-md-12">
	</div>
</div><!-- .row -->
	<div class="row asset" ng-repeat="asset in assets | filter:searchFilter">

	    
	    <div class="col-md-2">
	        <img class="img-responsive img-thumbnail" ng-src="{{asset.asset_thumbnail}}">
	    </div>
	    <div class="col-md-8">
	        <h3>nairobitsbarcode: {{asset.nairobitsbarcode}}</h3>
	        <h3>serialnumber: {{asset.serialnumber}}</h3>
	        <h3>Item category_id: {{asset.category_id}}</h3>
	        <h4>currentcenter_id: {{asset.currentcenter_id}}</h4>
	        <h5>specifications: {{asset.specifications}}</h5>
	        <h5>status: {{asset.status}}</h5>
	        <h5>condition: {{asset.condition}}</h5>
	        <h5>additional_information: {{asset.additional_information}}</h5>
	    </div>
	    <div class="col-md-2">
	        <a ng-href="user/{{asset.asset_id}}" class="btn btn-primary edit-user">Manage</a>
	    </div>            
	</div>
</section>