//import angular from 'angular';
//import uiDate from 'angular-ui-date';

//require('jquery-ui/themes/base/minified/jquery-ui.min.css');

// Declaring the module a module
var dashboard = angular.module('dashboard', ['conduction','ui.router','pascalprecht.translate','ngAnimate','ngTouch','ngSanitize','ui.bootstrap','summernote','ngStorage','ngMaterial', 'ngMessages', 'material.svgAssetsCache']);

// We want to do some basic checks when running the application
dashboard.run(function($rootScope, $state) {	
	
})

dashboard.factory('lazyService', [ '$http', function($http) {
    var jsPath = 'js/${ name }.js';
    var promisesCache = {};

    return {
        loadScript: function(name) {
            var path = jsPath.replace('${ name }', name);
            var promise = promisesCache[name];
            if (!promise) {
                promise = $http.get(path);
                promisesCache[name] = promise;

                return promise.then(function(result) {
                    eval(result.data);
                    console.info('Loaded: ' + path);
                });
            }

            return promise;
        }
    }
}]);

/*@tdo depraticed? */
dashboard.factory('api', [ 'ConductionApi', function(ConductionApi) {
    return ConductionApi;
}]);

dashboard.config(['$urlRouterProvider', '$stateProvider','$locationProvider','$controllerProvider', '$provide','$translateProvider', function($urlRouterProvider, $stateProvider, $locationProvider,$controllerProvider,$provide,$translateProvider) {

	//translations
    $translateProvider
        .useStaticFilesLoader({
            prefix: 'js/i18n/',
            suffix: '.json'
        })
        .fallbackLanguage('nl')    
        .registerAvailableLanguageKeys(['en', 'nl'], {
            'en_*': 'en',
            'nl_*': 'nl',
            '*': 'nl' 
        })
        .determinePreferredLanguage()
        .useSanitizeValueStrategy('sanitize');
        
    // loging missing translations to the console
    $translateProvider.useMissingTranslationHandlerLog();
  }]);

dashboard.controller('homeCtrl', ['$scope','$state','$filter','session','ConductionAPI',  function($scope,$state,$filter, session, ConductionAPI) {
    var $translate = $filter('translate');    
    
    $scope.session = session.get();
    $scope.page = [];
    $scope.page.title = "dashboard | Dashboard";     
    
 }])

  
dashboard.controller('dashboardCtrl', ['$scope','$state','$stateParams','$filter','session','$localStorage','$sessionStorage','ConductionAPI',  function($scope,$state,$stateParams, $filter, session, $localStorage, $sessionStorage, ConductionAPI) {
    var $translate = $filter('translate');    
    $scope.session = session.get();
    
	// we should kinda set this on the routing level          
    $scope.alerts = [];
    $scope.page = [];
    $scope.page.title = "dashboard | Welkom";    	
    
	// Lets set the importan variables
    $scope.session = session.get();
    $scope.user = $scope.session.user; 
}]);


/* The event Cotroller */
dashboard.controller('huwelijkController', ['$scope','$rootScope','$state','$stateParams','$localStorage','$sessionStorage','ConductionAPI','$sce',  function($scope,$rootScope, $state, $stateParams,$localStorage, $sessionStorage, ConductionAPI,$sce ) {

	//var huwelijk = angular.fromJson(localStorage.getItem('huwelijk'));
	$scope.$state = $state;
	$scope.$stateParams = $stateParams; 
	// Laten we eens proberen om shizle op te slaan in de local storage
	//$scope.$storage = $localStorage;
	$scope.$storage = $sessionStorage;
	
	$scope.display = "fullFlow";
	$scope.stage = false;	
	
	$scope.partnerConfirm = false;
	$scope.person = {};
	$scope.bsn = null;
	$scope.trouwdatumObject = null;
	$scope.aanvraagObject = null;
	$scope.meldingObject = null;
	$scope.partnerObject = null;
	$scope.bsnObject = null;
	$scope.error = null;
	$scope.loading = false;
	$scope.getuigen = [];

	
	// uitwisselbare objecten
	$scope.locatieObject = null;
	$scope.productObject = null;
	$scope.ambtenaarObject = null;
	
	
	
	$scope.availabledates = function(date) {
	    var day = date.getDay();
	    return day === 1 || day === 2 || day === 3|| day === 5;
	  };
	
	$scope.removeHuwelijk = function () {
		$scope.$storage.huwelijk = null;
		$scope.huwelijk = null;
	}
	$scope.setHuwelijk = function (huwelijk) {
		// We onst aloww a set null, remove huwelijk should be used for that
		if(!huwelijk){
			return false;
		}
		$scope.$storage.huwelijk = huwelijk;
		$scope.huwelijk = huwelijk;
		
		console.log('Huwelijk has been updated to:');
		console.log(huwelijk);
	}

	// Used to mark the current stage of the wedding form
	$scope.setStage = function (stage) {
		$scope.stage = stage;
	}
	// Used to mark the current stage of the wedding form
	$scope.setDisplay = function (display) {
		$scope.display = display;
	}
	/*
	 * This function handles posting the huwelijk back to the api
	 * 
	 */	
	$scope.postHuwelijk = function (huwelijk) {
		if(!huwelijk){
			var huwelijk = $scope.getHuwelijk();
		}


    	$scope.loading = true; 	
    	
    	/* @todo hier vangen we eigenlijk slechte validatie op 
    	if(!huwelijk.locaties){huwelijk.locaties = []}
    	if(!huwelijk.partners){huwelijk.partners = []}
    	if(!huwelijk.documenten){huwelijk.documenten = []}
    	if(!huwelijk.getuigen){huwelijk.getuigen = []}
    	if(!huwelijk.issues){huwelijk.issues = []} 
    	if(!huwelijk.additioneleProducten){huwelijk.additioneleProducten = []} 
    	*/
    	var post = {};
    	post.id = huwelijk.id;
    	post.tijd = huwelijk.tijd;
    	post.datum = huwelijk.datum;
    	post.type = huwelijk.type;
    	 
		ConductionAPI
		.all('huwelijk')
		.one('huwelijk',post.id)
		.customPUT(post).then(function successCallback(response) {
			//s$scope.setHuwelijk(response);	    
	    	$scope.loading = false; 	
	    	return response;	
	    }, function errorCallback(response) {
	    	alert( response.data['hydra:description']);
	    	$scope.loading = false; 	
	    }); 

	}
	

	
	/*
	 * Token
	 * 
	 */
	$scope.getToken = function (token) {
		$scope.loading = true; 	
    	ConductionAPI
		.all('tokens')
		.one('tokens',token)
		.get().then(function successCallback(response) {
			$scope.token = response;    
	    	$scope.loading = false; 	
	    	return response;	
	    }, function errorCallback(response) {
	    	alert( response.data['hydra:description']);
	    	console.log(response);
	    	$scope.loading = false; 	
	    }); 		
	}
	// Confirm a token action
	$scope.confirmToken = function (token) {
		if(!token){var token = $scope.token;}
		$scope.loading = true; 	
    	ConductionAPI
		.all('tokens')
		.one('tokens',token.token)
		.one('confirm')
		.post().then(function successCallback(response) {
			$scope.token = response;    
	    	$scope.loading = false; 	
	    	return response;	
	    }, function errorCallback(response) {
	    	alert( response.data['hydra:description']);
	    	console.log(response);
	    	$scope.loading = false; 	
	    }); 		
	}
	// Refuse a token action
	$scope.refuseToken = function (token) {
		if(!token){var token = $scope.token;}
		$scope.loading = true; 	
    	ConductionAPI
		.all('tokens')
		.one('tokens',token.token)
		.one('refuse')
		.post().then(function successCallback(response) {
			$scope.token = response;    
	    	$scope.loading = false; 	
	    	return response;	
	    }, function errorCallback(response) {
	    	alert( response.data['hydra:description']);
	    	console.log(response);
	    	$scope.loading = false; 	
	    }); 		
	}
	// Atach a token person to BRP
	$scope.setPersonOnToken = function (bsn) {
		var post = {}
		post.bsn = bsn;
		$scope.loading = true; 	
    	ConductionAPI
		.all('tokens')
		.one('tokens',token.id)
		.one('person')
		.post(post).then(function successCallback(response) {
			$scope.token = response;    
	    	$scope.loading = false; 	
	    	return response;	
	    }, function errorCallback(response) {
	    	alert( response.data['hydra:description']);
	    	console.log(response);
	    	$scope.loading = false; 	
	    }); 		
	}
	
	/*
	 * Payment
	 * 
	 */
	$scope.pay = function () {
		if($scope.huwelijk){
			
			$scope.loading = true; 	
	    	ConductionAPI
			.all('huwelijk')
			.one('huwelijk',$scope.huwelijk.id)
			.one('pay')
			.get([]).then(function successCallback(response) {
		    	//localStorage.setItem('huwelijk', angular.toJson(response));
		    	//$scope.huwelijk = response;
				window.location.href = response.url;	   
		    	$scope.loading = false; 	
		    	return response;	
		    }, function errorCallback(response) {
		    	alert( response.data['hydra:description']);
		    	console.log(response);
		    	$scope.loading = false; 	
		    }); 
		}
		else{
			alert('Je moet ingelogd zijn om te betalen')
		}
	}
	
	/*
	 * This function get the Huwelijk based on a BSN (wich is the return value of the digid login). What makes this slightly more complex is that we might need to bring the current object in sync this marrage
	 * 
	 */
    $scope.getHuwelijk = function (bsn) {
    	
    	// Lets first see if we can get it from local storrage
    	if($scope.$storage.huwelijk){
    		return $scope.$storage.huwelijk
    	}
    	
    	// Oke only then we are going to get it from the api
    	if(!bsn){
    		var bsn = $scope.bsn;
    	}
    	else{
    		$scope.bsn = bsn;
    	}
    	
    	var post = {}
    	post.bsn = String(bsn);

    	$scope.loading = true;
		ConductionAPI
		.all('huwelijk_bsn')
		.post(post).then(function successCallback(response) {
			$scope.setHuwelijk(response);	 	    	
	    	$scope.loading = false;
	    	return response;	
	    }, function errorCallback(response) {
	    	alert( response.data['hydra:description']);
	    	console.log(response);
	    	$scope.loading = false; 	
	    });  
    }

    $scope.login = function () {
    	window.location.href = "http://digispoof.zaakonline.nl?responce_url=" + encodeURI(window.location.href);
    }
    
    $scope.createPerson = function () {
    	
    }
    

    $scope.opslaan = function () {
    	$scope.huwelijk =  $scope.getHuwelijk();    	
    }
    
    
    // Ophalen van een Ambtenaar
    $scope.loadAmbtenaar = function (id) {
    	$scope.loading = true; 	
    	ConductionAPI
		.all('ambtenaar')
		.one('ambtenaar',id)
		.get().then(function successCallback(response) {
	    	//localStorage.setItem('huwelijk', angular.toJson(response));
	    	//$scope.huwelijk = response;
			$scope.ambtenaar = response;    
			if(response.film.url){
				$scope.ambtenaar.film.url = $sce.trustAsResourceUrl(response.film.url);
			}  		    	
	    	console.log(response);
	    	$scope.loading = false; 	
	    	return response;	
	    }, function errorCallback(response) {
	    	alert( response.data['hydra:description']);
	    	console.log(response);
	    	$scope.loading = false; 	
	    }); 
    }

    // Ophalen van een Product
    $scope.loadProduct = function (id) {
    	$scope.loading = true; 	
    	ConductionAPI
		.all('product')
		.one('product',id)
		.get().then(function successCallback(response) {
	    	//localStorage.setItem('huwelijk', angular.toJson(response));
	    	//$scope.huwelijk = response;
			$scope.product = response;	    
			if(response.film.url){
				$scope.product.film.url = $sce.trustAsResourceUrl(response.film.url);
			}  	
	    	console.log(response);
	    	$scope.loading = false; 	
	    	return response;	
	    }, function errorCallback(response) {
	    	alert( response.data['hydra:description']);
	    	console.log(response);
	    	$scope.loading = false; 	
	    }); 
    }
    

    // Ophalen van een Locatie
    $scope.loadLocatie = function (id) {
    	$scope.loading = true; 	
    	ConductionAPI
		.all('locaties')
		.one('locaties',id)
		.get().then(function successCallback(response) {
	    	//localStorage.setItem('huwelijk', angular.toJson(response));
	    	//$scope.huwelijk = response;
			$scope.locatie = response;	    
			if(response.film.url){
				$scope.locatie.film.url = $sce.trustAsResourceUrl(response.film.url);
			}
	    	console.log(response);
	    	$scope.loading = false; 	
	    	return response;	
	    }, function errorCallback(response) {
	    	alert( response.data['hydra:description']);
	    	console.log(response);
	    	$scope.loading = false; 	
	    }); 
    }
    $scope.sendMelding = function () {
    	$scope.meldingObject = true;
    	
    	if($scope.huwelijk){

	    	$scope.loading = true; 	
	    	
	    	ConductionAPI
    		.all('huwelijk')
			.one('huwelijk',$scope.huwelijk.id)
    		.all('melding')
    		.post().then(function successCallback(response) {
    			$scope.setHuwelijk(response);	 
    	    	$scope.loading = false; 	
    	    	return response;	
    	    }, function errorCallback(response) {
    	    	alert( response.data['hydra:description']);
    	    	console.log(response);
    	    	$scope.loading = false; 	
    	    }); 
    	}
		console.log("Melding gedaan"); 
    }

    $scope.sendAanvraag = function () {
    	$scope.aanvraagObject = true; 

    	if($scope.huwelijk){

	    	$scope.loading = true; 	
	    	
	    	ConductionAPI
    		.all('huwelijk')
			.one('huwelijk',$scope.huwelijk.id)
    		.all('aanvraag')
    		.post().then(function successCallback(response) {
    			$scope.setHuwelijk(response);	 
    	    	$scope.loading = false; 	
    	    	return response;	
    	    }, function errorCallback(response) {
    	    	alert( response.data['hydra:description']);
    	    	console.log(response);
    	    	$scope.loading = false; 	
    	    }); 
    	}
		console.log("Aanvraag gedaan");    	
    }
    
    $scope.addGetuige = function (getuige) {
		$scope.getuigen.push(getuige);
		
		if($scope.huwelijk){

	    	$scope.loading = true; 	
	    	
	    	ConductionAPI
    		.all('huwelijk')
			.one('huwelijk',$scope.huwelijk.id)
    		.all('addWitness')
    		.post(getuige).then(function successCallback(response) {
    			$scope.setHuwelijk(response);	 	
    	    	$scope.loading = false;
    	    	// We dont set loading to false becouse het get huwelijk already does that
    	    	return response;	
    	    }, function errorCallback(response) {
    	    	alert( response.data['hydra:description']);
    	    	console.log(response);
    	    	$scope.loading = false; 	
    	    }); 
    	}
		else{
			alert('Je moet ingelogd zijn om een getuige toe te voegen')
		}	
	}

    $scope.addBijzonderAmbtenaar = function (bijzonderAmbtenaar) {
		if($scope.huwelijk){

	    	$scope.loading = true; 	
	    	
	    	ConductionAPI
    		.all('huwelijk')
			.one('huwelijk',$scope.huwelijk.id)
    		.all('requestSpecial')
    		.post(bijzonderAmbtenaar).then(function successCallback(response) {
    			$scope.setHuwelijk(response);	 	
    	    	$scope.loading = false;
    	    	// We dont set loading to false becouse het get huwelijk already does that
    	    	return response;	
    	    }, function errorCallback(response) {
    	    	alert( response.data['hydra:description']);
    	    	console.log(response);
    	    	$scope.loading = false; 	
    	    }); 
    	}
		else{
			alert('Je moet ingelogd zijn om een bijzonder trouw ambtenaar toe te voegen')
		}		
	}
    
    /// Indienen van een issue
    $scope.sendIssue = function (issue) {  	

    	var post = issue;
    	
    	// Als we een huwelijk open hebben staan dan koppelen we de issue aan een huwelijkd
    	if($scope.huwelijk){
    		post.huwelijk = {}; 
    		post.huwelijk = $scope.huwelijk['@id'];
    	}
    	
    	// Daadwerkelijk inschieten van de issue
    	$scope.loading = true;
		ConductionAPI
		.all('issues')
		.post(post).then(function successCallback(response) {
			// Als er een huwelijk is moeten we die opnieuw inladen
	    	if($scope.huwelijk){
    			//$scope.huwelijk = response;
    			$scope.issueResponce = response;
	    	}
	    	else{
	    		$scope.loading = false; 
	    	}

			$scope.issueResponce = response;
  	    		
		  }, function errorCallback(response) {
  	    	alert( response.data['hydra:description']);
  	    	console.log(response);
  	    	$scope.loading = false; 	
  	    });
	}
    
	// Instllen van een BSN @depracticed
	$scope.setBSN = function (bsn) {
		$scope.bsn = bsn;
		console.log($scope.bsn); 	
		
	}	
	

	$scope.removePartner = function () {
		$scope.partnerObject = null;
		console.log($scope.partnerObject); 	
	}
	
	$scope.setPartner = function (partner) {
		var post = partner;
		if($scope.huwelijk){

	    	$scope.loading = true; 	
	    	
	    	ConductionAPI
    		.all('huwelijk')
			.one('huwelijk',$scope.huwelijk.id)
    		.all('addPartner')
    		.post(post).then(function successCallback(response) {
    			$scope.setHuwelijk(response);	 
    	    	$scope.loading = false;
    	    	//window.location.href = "/";
    	    	return response;	
    	    }, function errorCallback(response) {
    	    	alert( response.data['hydra:description']);
    	    	$scope.loading = false; 
    	    	return response;		
    	    }); 
    	}
		else{
			alert('Je moet ingelogd zijn om een partner toe te voegen')
		}		
	}

	$scope.setLocatie = function (location) {
		$scope.locationObject = location;
		
		var post = {}
		post.setLocation = location.id;
		if($scope.huwelijk){

	    	$scope.loading = true; 	
	    	
	    	ConductionAPI
    		.all('huwelijk')
			.one('huwelijk',$scope.huwelijk.id)
    		.all('setLocation')
    		.post(post).then(function successCallback(response) {
    			$scope.setHuwelijk(response);	 
    	    	$scope.loading = false;
    	    	window.location.href = "/";
    	    	return response;	
    	    }, function errorCallback(response) {
    	    	alert( response.data['hydra:description']);
    	    	console.log(response);
    	    	$scope.loading = false; 	
    	    }); 
    	}
		else{
			alert("U moet eerst inloggen voordat u een huwelijks reservering kan invullen :)");
		}	
	}
	
	$scope.setAmbtenaar = function (ambtenaar) {
		$scope.ambtenaarObject = ambtenaar;
		
		var post = {}
		post.setOfficial = ambtenaar.id;
		if($scope.huwelijk){

	    	$scope.loading = true; 	
	    	
	    	ConductionAPI
    		.all('huwelijk')
			.one('huwelijk',$scope.huwelijk.id)
    		.all('requestOfficial')
    		.post(post).then(function successCallback(response) {
    			$scope.setHuwelijk(response);	 
    	    	$scope.loading = false;
    	    	window.location.href = "/";
    	    	return response;	
    	    }, function errorCallback(response) {
    	    	alert( response.data['hydra:description']);
    	    	console.log(response);
    	    	$scope.loading = false; 	
    	    }); 
    	}
		else{
			alert("U moet eerst inloggen voordat u een huwelijks reservering kan invullen :)");
		}
		
		console.log($scope.ambtenaarObject); 	
	}	
		
	$scope.removeLocation = function () {
		$scope.locationObject = null;
		console.log($scope.locationObject); 	
	}
	
	$scope.setProduct = function (product) {
		$scope.productObject = product;
		
		var post = {};
		post.setProduct = product.id;
		if($scope.huwelijk){

	    	$scope.loading = true; 	
	    	
	    	$scope.loading = true;
			ConductionAPI
    		.all('huwelijk')
			.one('huwelijk',$scope.huwelijk.id)
    		.all('setProduct')
    		.post(post).then(function successCallback(response) {
    			$scope.setHuwelijk(response);	 
    	    	$scope.loading = false;
    	    	//window.location.href = "/";
    	    	return response;	
    	    }, function errorCallback(response) {
    	    	alert( response.data['hydra:description']);
    	    	console.log(response);
    	    	$scope.loading = false; 	
    	    }); 
    	}
		else{
			alert("U moet eerst inloggen voordat u een huwelijks reservering kan invullen :)");
		}

		console.log($scope.productObject); 	
		
	}
	
	$scope.removeProduct = function () {
		$scope.productObject = null;
		console.log($scope.productObject); 	
		
	}
	
	
	

	$scope.removeAmbtenaar = function () {
		$scope.ambtenaarObject = null;
		
		
		
		console.log($scope.ambtenaarObject); 	
	}
	
	$scope.setTrouwdatum = function (trouwdatum) {
		$scope.trouwdatumObject = trouwdatum;
		console.log($scope.trouwdatum); 	
	}

	$scope.removeTrouwdatum = function () {
		$scope.trouwdatumObject = null;
		console.log($scope.trouwdatum); 	
	}
	
    // We capture the get actions in an function so that we can trigger a reload from the scop
    $scope.load = function (bsn) {

    	if($scope.$storage.huwelijk){
    		$scope.huwelijk =$scope.$storage.huwelijk;    		
    	}
    	if(bsn && !$scope.$storage.huwelijk){
    		$scope.huwelijk = $scope.getHuwelijk(bsn);
    	}
    	
    	// Ophalen van locaties, ambtenaderen en producten INDIEN NODIG in princiepe staan deze in storage
    	
    	if(!$scope.$storage.locaties || $scope.$storage.locaties.length == 0){
	    	ConductionAPI
			.all('locaties')
			.getList() 
			.then(function successCallback(response) {
		    	$scope.$storage.locaties = response; 
	        	console.log($scope.$storage.locaties);
		    }, function errorCallback(response) {
		    	alert( response.data['hydra:description']);
		    	console.log(response);	
		    });  
    	}

    	if(!$scope.$storage.ambtenaren || $scope.$storage.ambtenaren.length == 0){
	    	ConductionAPI
			.all('ambtenaren')
			.getList() 
			.then(function successCallback(response) {
		    	$scope.$storage.ambtenaren = response; 
	        	console.log($scope.$storage.ambtenaren);
		    }, function errorCallback(response) {
		    	alert( response.data['hydra:description']);
		    	console.log(response);
		    });
    	}

    	if(!$scope.$storage.producten || $scope.$storage.producten.length == 0){
	    	ConductionAPI
			.all('producten')
			.getList() 
			.then(function successCallback(response) {
		    	$scope.$storage.producten = response; 	
	        	console.log($scope.$storage.producten);
		    }, function errorCallback(response) {
		    	alert( response.data['hydra:description']);
		    	console.log(response);
		    });
    	}
    	
    	
    	
    }

}]);
  