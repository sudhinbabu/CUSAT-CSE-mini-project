SELECT 
    courses.name
FROM
    allotments
        JOIN
    courses ON courses.course_id = allotments.course_id
WHERE
    allotments.user_id = 35;