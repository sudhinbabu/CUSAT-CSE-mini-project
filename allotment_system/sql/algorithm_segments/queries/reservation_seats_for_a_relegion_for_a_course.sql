SELECT  departments.seats * religions.reservation_percentage DIV 100 AS seats_for_relegion , 
religions.religion_id,
departments.course_name ,
religions.name , religions.category , 
religions.caste_name  FROM departments JOIN religions WHERE departments.course_id = 4 AND religions.religion_id =1 ;