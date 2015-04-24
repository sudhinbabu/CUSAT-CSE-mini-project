SELECT 
    SUM(marks.awarded_mark) / SUM(marks.max_mark) * 500 AS prefernce_index , marks.user_id
FROM
    courses
        JOIN
    course_dependencies ON courses.course_id = course_dependencies.course_id
        JOIN
    marks ON course_dependencies.dependency = marks.subject_name
WHERE
    courses.course_id = 1 AND user_id = 2;