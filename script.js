//ユーザー登録
const signUp = () => {
  const name = document.getElementById("signUpInputName").value;
  const bio = document.getElementById("signUpInputBio").value;
  const email = document.getElementById("signUpInputEmail").value;
  const password = document.getElementById("signUpInputPwd").value;
  const password_confirmation = document.getElementById("signUpInputCfmPwd").value
  const data = {
    sign_up_user_params: {
      name: name,
      bio: bio,
      email: email,
      password: password,
      password_confirmation: password_confirmation
    }
  };
  fetch("https://teachapi.herokuapp.com/sign_up", {
    method: "POST",
    headers: {
      "Content-Type": "application/json; charset=utf-8",
    },
    body: JSON.stringify(data),
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
    localStorage.setItem("id", json.id);
    localStorage.setItem("token", json.token)
  });
};

// ユーザーログイン
const signIn = () => {
  const email = document.getElementById("signInInputEmail").value;
  const password = document.getElementById("signInInputPwd").value;
  const passwordConfirmation = document.getElementById("signInInputCfmPwd").value;
  const data = {
    sign_in_user_params: {
      email: email,
      password: password,
      password_confirmation: passwordConfirmation
    }
  };
  fetch("https://teachapi.herokuapp.com/sign_in", {
    method: "POST",
    headers: {
      "Content-Type": "application/json; charset=utf-8",
    },
    body: JSON.stringify(data),
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
    localStorage.setItem("id", json.id)
    localStorage.setItem("token", json.token)
  });
};

// ユーザー一覧
const userList = () => {
  const userPageNumber = document.getElementById("userListPageNum").value;
  const userPageLimit = document.getElementById("userListLimit").value;
  const userSearch = document.getElementById("userListSearch").value;
  const userList = document.getElementById("userList");
  const token = localStorage.getItem("token");
  fetch(`https://teachapi.herokuapp.com/users?page=${userPageNumber}&limit=${userPageLimit}&query=${userSearch}`, {
    method: "GET",
    headers: {
      "Content-Type": "application/json; charset=utf-8",
      "Authorization": "Bearer " + token
    }
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
    let br = "<br>";
    for (let i = 0; i < json.length; i++) {
      const obj = json[i];
      const objstr = JSON.stringify(obj.name);
      br += objstr + "<br>";
    }
    userList.innerHTML = br;
  });
};

// 投稿一覧
const postList = () => {
  const postPageNumber = document.getElementById("postPageNum").value;
  const postPageLimit = document.getElementById("postLimit").value;
  const postSearch = document.getElementById("postSearch").value;
  const postList = document.getElementById("postList");
  const token = localStorage.getItem("token");
  fetch(`https://teachapi.herokuapp.com/posts?page=${postPageNumber}&limit=${postPageLimit}&query=${postSearch}`, {
    method: "GET",
    headers: {
      "Content-Type": "application/json; charset=utf-8",
      "Authorization": "Bearer " + token
    }
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
    let br = "<br>";
    for (let i = 0; i < json.length; i++) {
      const obj = json[i];
      const objstr = JSON.stringify(obj.text);
      br += objstr + "<br>";
    }
    postList.innerHTML = br;
  });
};

// ユーザー編集
const editUser = () => {
  const editName = document.getElementById("editName").value;
  const editBio = document.getElementById("editBio").value;
  const data = {
    user_params: {
      name: editName,
      bio: editBio,
    }
  };
  const id = localStorage.getItem("id");
  const token = localStorage.getItem("token");
  fetch(`https://teachapi.herokuapp.com/users/${id}`, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json; charset=utf-8",
      "Authorization": "Bearer " + token
    },
    body: JSON.stringify(data),
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
  });
};

// ユーザー削除
const deleteUser = () => {
  const id = document.getElementById("deleteUser").value;
  const token = localStorage.getItem("token");
  fetch(`https://teachapi.herokuapp.com/users/${id}`, {
    method: "DELETE",
    headers: {
      "Content-Type": "application/json; charset=utf-8",
      "Authorization": "Bearer " + token
    },
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
  });
};

// タイムライン
const timeline = () => {
  const tlId = document.getElementById("tlId").value;
  const tlPageNumber = document.getElementById("tlPageNum").value;
  const tlPageLimit = document.getElementById("tlLimit").value;
  const tlSearch = document.getElementById("tlSearch").value;
  const tlList = document.getElementById("tlList");
  const token = localStorage.getItem("token");
  fetch(`https://teachapi.herokuapp.com/users/${tlId}/timeline?page=${tlPageNumber}&limit=${tlPageLimit}&query=${tlSearch}`, {
    method: "GET",
    headers: {
      "Content-Type": "application/json; charset=utf-8",
      "Authorization": "Bearer " + token
    }
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
    let br = "<br>";
    for (let i = 0; i < json.length; i++) {
      const obj = json[i];
      const objstr = JSON.stringify(obj.text);
      br += objstr + "<br>";
    }
    tlList.innerHTML = br;
  });
};

// 投稿作成
const submitPost = () => {
  const createPost = document.getElementById("createPost").value;
  const data = {
    post_params: {
      text: createPost
    }
  };
  const token = localStorage.getItem("token");
  fetch("https://teachapi.herokuapp.com/posts", {
    method: "POST",
    headers: {
      "Content-Type": "application/json; charset=utf-8",
      "Authorization": "Bearer " + token
    },
    body: JSON.stringify(data),
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
  });
}

// 投稿編集
const editPost = () => {
  const id = document.getElementById("editPostId").value;
  const editText = document.getElementById("editText").value;
  const data = {
    post_params: {
      text: editText
    }
  };
  const token = localStorage.getItem("token");
  fetch(`https://teachapi.herokuapp.com/posts/${id}`, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json; charset=utf-8",
      "Authorization": "Bearer " + token
    },
    body: JSON.stringify(data),
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
  });
}

// 投稿削除
const deletePost = () => {
  const id = document.getElementById("deletePostId").value;
  const token = localStorage.getItem("token");
  fetch(`https://teachapi.herokuapp.com/posts/${id}`, {
    method: "DELETE",
    headers: {
      "Content-Type": "application/json; charset=utf-8",
      "Authorization": "Bearer " + token
    },
  })
  .then(response => {
    const json = response.json();
    return json;
  })
  .then(json => {
    console.log(json);
  });
}