import "./bootstrap";
import "flowbite";

// navbar
window.addEventListener("scroll", function () {
    var navbar = document.querySelector(".navbar");
    if (window.scrollY > 50) {
        navbar.classList.add("scrolled");
    } else {
        navbar.classList.remove("scrolled");
    }
});

//edit task
document.addEventListener("DOMContentLoaded", function () {
    const editTaskBtns = document.querySelectorAll(".editTaskBtn");
    const editForm = document.querySelectorAll(".editTaskForm");
    const cancelBtns = document.querySelectorAll(".cancelTaskBtn");
    const deleteBtns = document.querySelectorAll(".deleteTaskBtn");
    const statusDropdowns = document.querySelectorAll(".change-status");

    if (editTaskBtns && editForm && cancelBtns) {
        editTaskBtns.forEach(function (btn, index) {
            btn.addEventListener("click", function () {
                const taskId = btn.getAttribute("data-task-id");
                const modal = document.querySelector(
                    `.editTaskForm[data-task-id="${taskId}"]`
                );
                // console.log(modal);
                if (modal) {
                    if (!modal.classList.contains("flex")) {
                        modal.classList.add("flex");
                    } else {
                        modal.classList.remove("flex");
                    }
                    modal.classList.toggle("hidden");
                } else {
                    console.error(
                        `Modal untuk task ID ${taskId} tidak ditemukan`
                    );
                }
            });
        });

        cancelBtns.forEach(function (btn) {
            btn.addEventListener("click", function () {
                const taskId = btn.getAttribute("data-task-id");
                const modal = document.querySelector(
                    `.editTaskForm[data-task-id="${taskId}"]`
                );
                if (modal) {
                    if (!modal.classList.contains("flex")) {
                        modal.classList.add("flex");
                    } else {
                        modal.classList.remove("flex");
                    }
                    modal.classList.toggle("hidden");
                } else {
                    console.error(
                        `Modal untuk task ID ${taskId} tidak ditemukan`
                    );
                }
            });
        });

        if (deleteBtns) {
            deleteBtns.forEach(function (btn) {
                btn.addEventListener("click", function () {
                    const taskId = btn.getAttribute("data-task-id");
                    if (taskId) {
                        if (
                            confirm(
                                "Apakah Anda yakin ingin menghapus Task ini?"
                            )
                        ) {
                            const postToDelete = document.querySelector(
                                `.post[data-task-id="${taskId}"]`
                            );
                            if (postToDelete) {
                                postToDelete.remove(); //hapus data dari DOM
                                // Lakukan juga permintaan DELETE ke server di sini
                                deleteTask(taskId);
                            } else {
                                alert("Gagal menghapus produk");
                            }
                        }
                    }
                });
            });
        }

        function deleteTask(taskId) {
            console.log("Deleting Task with ID:", taskId);
            // temukan rute delete
            fetch(`tasks/delete/${taskId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert("Task berhasil dihapus");
                    } else {
                        alert("Gagal menghapus Task dari server");
                    }
                });
        }

        statusDropdowns.forEach(function (dropdown) {
            dropdown.addEventListener("change", function () {
                const taskId = this.getAttribute("data-task-id");
                const newStatusId = this.value;
                // const taskItem = this.closest(".task-item");

                fetch(`/tasks/${taskId}/update-status`, {
                    method: "PATCH",
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        status_id: newStatusId,
                    }), //Fungsi body: JSON.stringify({ status_id: newStatusId }):
                    // Tujuan: Untuk mengirimkan data status_id dalam format JSON ke server, yang akan diproses oleh controller Laravel (updateStatus) untuk memperbarui status task.
                    // Konversi ke JSON: Mengubah objek menjadi string JSON sehingga bisa dibaca oleh server. Server kemudian akan memparse JSON tersebut kembali menjadi objek untuk diproses.
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            alert("Status berhasil diperbarui!");
                            location.reload(); // Ini akan merefresh halaman
                            // if (newStatusId == 3) {
                            //     taskItem.classList.add("completed");
                            // } else {
                            //     taskItem.classList.remove("completed");
                            // }
                        } else {
                            alert("Gagal memperbarui status.");
                        }
                    })
                    .catch((error) => {
                        console.error("Error updating status:", error);
                        alert("Terjadi kesalahan. Coba lagi nanti.");
                    });
            });
        });
    }
});
