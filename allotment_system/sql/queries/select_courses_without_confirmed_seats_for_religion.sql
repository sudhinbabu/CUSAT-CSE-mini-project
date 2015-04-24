SELECT 
courses.course_id,applications.alloted_course_id,courses.seats,applications.religion_id,
 COUNT(applications.status ) AS alloted_seats,
applications.status
FROM
    courses
JOIN applications
 ON courses.course_id = applications.alloted_course_id
 GROUP BY courses.course_id
 HAVING applications.status = 'alloted' 