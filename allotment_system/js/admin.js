var newsApp = angular.module('newsApp',[]);

newsApp.controller('newsAppController', function($scope , $http ){

    $scope.addNews = function(){
        var response =   $http({
            method: 'POST',
            url: 'actions.php',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            transformRequest: function(obj) {
                var str = [];
                for(var p in obj)
                    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                return str.join("&");
            },
            data: {'addNews': 'yes','news': $scope.newsText}
        });

        response.success(function(data, status, headers, config) {
            console.log(data);
            // location.reload();
            $scope.get_news_list();

        });
        response.error(function(data, status, headers, config) {
         console.log(data.msg);
     });
    }



    $scope.deleteNews = function(news_id,$index){
        $scope.list_news.splice($index,1);     
        var response =   $http({
            method: 'POST',
            url: 'actions.php',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            transformRequest: function(obj) {
                var str = [];
                for(var p in obj)
                    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                return str.join("&");
            },
            data: {'deleteNews':'yes','news_id':news_id}
        });

        response.success(function(data, status, headers, config) {
           // $scope.get_news_list();
           console.log('success');
        });
        response.error(function(data, status, headers, config) {
         console.log(data.msg);
     });
    }

// SELECT users.user_id , ( SUM(marks.max_mark) DIV SUM (marks.awarded_mark) * 1500) AS index_mark FROM users JOIN marks ON marks.user_id = users.user_id;

    $scope.updateNews = function(news_id,news){  
        var response =   $http({
            method: 'POST',
            url: 'actions.php',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            transformRequest: function(obj) {
                var str = [];
                for(var p in obj)
                    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                return str.join("&");
            },
            data: {'updateNews':'yes','news_id':news_id,'news': news}
        });

        response.success(function(data, status, headers, config) {
            alert('successfully updated news');
           // $scope.get_news_list();
           console.log('success');
        });
        response.error(function(data, status, headers, config) {
         console.log(data.msg);
     });
    }






});


newsApp.run(function($http,$rootScope){
//this function runs implicitly when angular js is loaded.

$rootScope.get_news_list = function(){
 var response =  $http.get('actions.php/?list_news=yes');

        response.success(function(data, status, headers, config) {
           if(data.news_count!=0){
              $rootScope.list_news = data;
           }
            // location.reload();

        });
        response.error(function(data, status, headers, config) {
         console.log(data.msg);
     });
}

$rootScope.get_news_list();

});
