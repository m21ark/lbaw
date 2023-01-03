# LBAW 2261

**Nexus** is a revolutionary new social network that connects people from all walks of life. With a sleek and intuitive interface, users can easily create profiles, share their interests and passions by making posts, comments and likes and by creating or joining pre-existing thematic groups to connect with friends and family. This communication is further facilitated by Nexus built-in chat with possibility to make video calls so you can be closer to the ones you love.

## Docker Command

The the full Docker command needed to start the image available at the group's GitLab Container Registry using the production database can be found below:

> sudo docker run -it -p 8000:80 --name=lbaw2261 -e DB_DATABASE="lbaw2261" -e DB_SCHEMA="lbaw2261" -e DB_USERNAME="lbaw2261" -e DB_PASSWORD="cGtzVMep" git.fe.up.pt:5050/lbaw/lbaw2223/lbaw2261

## Production URL

URL to our Nexus production product: [lbaw2261.lbaw.fe.up.pt](lbaw2261.lbaw.fe.up.pt)

## Access Credentials

### Administration Credentials

| Username | Email            | Password |
| -------- |----------------  | -------- |
| Admin    | admin@gmail.com  | password |

### User Credentials

| Type          | Username  | Password |
| ------------- | --------- | --------  |
| User     | user@gmail.com   | password |
| Banned User  | banned@example.net   | password |

## Team

- David Ferreira, up202006302@g.uporto.pt
- João Alves, up202007614@g.uporto.pt
- Marco André, up202004891@g.uporto.pt
- Ricardo Matos, up202007962@g.uporto.pt
