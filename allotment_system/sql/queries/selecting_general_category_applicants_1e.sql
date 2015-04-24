SELECT 
    applications.user_id,marks.subject_name,
	( SUM(marks.awarded_mark) / sum(marks.max_mark) * 1500  ) AS index_mark  
FROM
    applications
        JOIN
    marks ON applications.user_id = marks.user_id
GROUP BY 
	applications.user_id
HAVING 
	marks.subject_name = 'total_marks'
ORDER BY
   index_mark DESC