import mysql.connector

def convert_file_to_blob(file_path):
    try:
        with open(file_path, 'rb') as file:
            blob_data = file.read()
        return blob_data
    except Exception as e:
        print(f"An error occurred while converting file to blob: {e}")
        return None

def update_movie_poster(ranking, file_path):
    try:
        conn = mysql.connector.connect(
            host='YOUR_HOST',
            port=YOUR_PORT,
            user='YOUR_USER,
            password='YOUR_PW',
            database='YOUR_DB',
            charset='utf8mb4',
            use_unicode=True
        )
        cursor = conn.cursor()

        movie_poster = convert_file_to_blob(file_path)
        
        if movie_poster is not None:
            cursor.execute('''
                UPDATE movie
                SET movie_poster = %s
                WHERE ranking = %s
            ''', (movie_poster, ranking))

            conn.commit()
            print(f"Movie poster updated for ranking {ranking}")
        else:
            print(f"Failed to update movie poster for ranking {ranking}")

    except Exception as e:
        print(f"An error occurred while updating movie poster: {e}")
        conn.rollback()
    finally:
        cursor.close()
        conn.close()

if __name__ == '__main__':
    # 예시: 랭킹 1번 영화에 이미지 파일 업데이트
    ranking = 1
    file_path = 'YOUR_FILE_PATH'
    update_movie_poster(ranking, file_path)
    
    # 필요에 따라 다른 랭킹의 영화 포스터도 업데이트할 수 있습니다.
    ranking = 2
    file_path = 'YOUR_FILE_PATH'
    update_movie_poster(ranking, file_path)

    ranking = 3
    file_path = 'YOUR_FILE_PATH'
    update_movie_poster(ranking, file_path)

    ranking = 4
    file_path = 'YOUR_FILE_PATH'
    update_movie_poster(ranking, file_path)

    ranking = 5
    file_path = 'YOUR_FILE_PATH'
    update_movie_poster(ranking, file_path)

    
