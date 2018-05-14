document.getElementById("clearCompleted").onclick = function () {
    if (confirm("Do you wish to clear all the completed tasks?"))
	{
		location.href = "/complete/clear";
	}
	else
		location.href = "/";
};
