# 영화진흥회 Open API를 사용하여 Python으로 mariaDB에 값 삽입과 이를 통한 php 인터페이스 구현 
Inserting values ​​into DB using Python using Korea Film Council Open API and implementing PHP server through this
---
## step 1) DB설계 및 구축.


ERD


![image](https://github.com/savom/php-db-python/assets/56549021/aab3b4d3-53ca-40f5-b512-a51d5e1d7403)


Database_GuideLine_for_mariaDB.pptx 파일을 참고하여 DB를 설계

## step 2) Python을 통한 Open API 값과 DB 테이블에 값 삽입.


example)python_to_DB.py 파일을 사용하여 step 1에서 만들어 놓은 DB table에 Open API에서 받아온 값을 삽입하는 코드와 Python과 mariaDB의 연결
<pre><code>
##import time
##import requests
##import mysql.connector
##import pymysql
##
### API 키와 URL 설정
##MOVIE_API_KEY = 'your_key'
##BOXOFFICE_API_URL = f'http://www.kobis.or.kr/kobisopenapi/webservice/rest/boxoffice/searchDailyBoxOfficeList.json?key={MOVIE_API_KEY}&targetDt='
##MOVIE_INFO_API_URL = f'http://www.kobis.or.kr/kobisopenapi/webservice/rest/movie/searchMovieInfo.json?key={MOVIE_API_KEY}&movieCd='
##
##def fetch_movie_info(movie_code):
##    try:
##        response = requests.get(MOVIE_INFO_API_URL + movie_code)
##        response.raise_for_status()
##        movie_info_data = response.json()['movieInfoResult']['movieInfo']
##
##        movie_info = {
##            'movieCd': movie_code,
##            'directors': [director['peopleNm'] for director in movie_info_data['directors']],
##            'actors': [actor['peopleNm'] for actor in movie_info_data['actors']],
##            'genres': [genre['genreNm'] for genre in movie_info_data['genres']],
##            'openDt': movie_info_data['openDt'] if 'openDt' in movie_info_data else None,
##            'showTm': movie_info_data['showTm'] if 'showTm' in movie_info_data else None,
##            'watchGradeNm': movie_info_data['audits'][0]['watchGradeNm'] if movie_info_data['audits'] else None
##        }
##
##        return movie_info
##    except Exception as e:
##        print(f"An error occurred while fetching movie info for {movie_code}: {e}")
##        return None
##
##def fetch_boxoffice_data():
##    try:
##        yesterday = time.strftime('%Y%m%d', time.localtime(time.time() - 86400))  # 어제 날짜로 데이터 가져오기
##        response = requests.get(BOXOFFICE_API_URL + yesterday)
##        response.raise_for_status()
##        boxoffice_data = response.json()['boxOfficeResult']['dailyBoxOfficeList']
##
##        detailed_boxoffice_data = []
##        for movie in boxoffice_data:
##            movie_code = movie['movieCd']
##            movie_info = fetch_movie_info(movie_code)
##            if movie_info:
##                detailed_boxoffice_data.append({
##                    'rank': movie['rank'],
##                    'movieCd': movie_code,
##                    'movieNm': movie['movieNm'],
##                    'openDt': movie_info['openDt'],
##                    'showTm': movie_info['showTm'],
##                    'watchGradeNm': movie_info['watchGradeNm'],
##                    'directors': ', '.join(movie_info['directors']),
##                    'actors': ', '.join(movie_info['actors']),
##                    'genres': ', '.join(movie_info['genres']),
##                })
##
##        return detailed_boxoffice_data
##    except Exception as e:
##        print(f"An error occurred while fetching box office data: {e}")
##        return []
##
##def save_to_database(data):
##    try:
##        conn = mysql.connector.connect(
##            host='your_host',
##            port=your_port,
##            user='your_id',
##            password='your_pw',
##            database='your_db_name'
##        )
##        #conn = pymysql.connect(host = "your_host", port = your_port,  user = "your_user", password = "your_PW", db = "your_db", charset ="utf8") 
##        cursor = conn.cursor()
##
##        for movie in data:
##            cursor.execute('''
##                INSERT INTO movie (
##                    movie_num, movie_title, movie_character, movie_genre, movie_Date, movie_running, movie_age
##                ) VALUES (%s, %s, %s, %s, %s, %s, %s)
##                ON DUPLICATE KEY UPDATE
##                    movie_title=VALUES(movie_title), movie_character=VALUES(movie_character), movie_genre=VALUES(movie_genre),
##                    movie_Date=VALUES(movie_Date), movie_running=VALUES(movie_running), movie_age=VALUES(movie_age)
##                    
##            ''', (
##                movie['movieCd'], movie['movieNm'], movie['directors'] + ', ' + movie['actors'], movie['genres'], 
##                movie['openDt'], movie['showTm'], movie['watchGradeNm']
##            ))
##
##        conn.commit()
##        conn.close()
##    except Exception as e:
##        print(f"An error occurred while saving data to the database: {e}")
##
##def update_data_periodically():
##    while True:
##        boxoffice_data = fetch_boxoffice_data()
##        if boxoffice_data:
##            save_to_database(boxoffice_data)
##        time.sleep(43200)  # 12시간마다 데이터 업데이트
##
##if __name__ == '__main__':
##    update_data_periodically()

import time
import requests
import mysql.connector

# API 키와 URL 설정
MOVIE_API_KEY = 'your_key'
BOXOFFICE_API_URL = f'http://www.kobis.or.kr/kobisopenapi/webservice/rest/boxoffice/searchDailyBoxOfficeList.json?key={MOVIE_API_KEY}&targetDt='
MOVIE_INFO_API_URL = f'http://www.kobis.or.kr/kobisopenapi/webservice/rest/movie/searchMovieInfo.json?key={MOVIE_API_KEY}&movieCd='

def fetch_movie_info(movie_code):
    try:
        response = requests.get(MOVIE_INFO_API_URL + movie_code)
        response.raise_for_status()
        movie_info_data = response.json()['movieInfoResult']['movieInfo']

        movie_info = {
            'movieCd': movie_code,
            'directors': [director['peopleNm'] for director in movie_info_data['directors']],
            'actors': [actor['peopleNm'] for actor in movie_info_data['actors']],
            'genres': [genre['genreNm'] for genre in movie_info_data['genres']],
            'openDt': movie_info_data['openDt'] if 'openDt' in movie_info_data else None,
            'showTm': movie_info_data['showTm'] if 'showTm' in movie_info_data else None,
            'watchGradeNm': movie_info_data['audits'][0]['watchGradeNm'] if movie_info_data['audits'] else None
        }

        return movie_info
    except Exception as e:
        print(f"An error occurred while fetching movie info for {movie_code}: {e}")
        return None

def fetch_boxoffice_data():
    try:
        yesterday = time.strftime('%Y%m%d', time.localtime(time.time() - 86400))  # 어제 날짜로 데이터 가져오기
        response = requests.get(BOXOFFICE_API_URL + yesterday)
        response.raise_for_status()
        boxoffice_data = response.json()['boxOfficeResult']['dailyBoxOfficeList']

        detailed_boxoffice_data = []
        for movie in boxoffice_data:
            movie_code = movie['movieCd']
            movie_info = fetch_movie_info(movie_code)
            if movie_info:
                detailed_boxoffice_data.append({
                    'rank': movie['rank'],
                    'movieCd': movie_code,
                    'movieNm': movie['movieNm'],
                    'openDt': movie_info['openDt'],
                    'showTm': movie_info['showTm'],
                    'watchGradeNm': movie_info['watchGradeNm'],
                    'directors': ', '.join(movie_info['directors']),
                    'actors': ', '.join(movie_info['actors']),
                    'genres': ', '.join(movie_info['genres']),
                })

        return detailed_boxoffice_data
    except Exception as e:
        print(f"An error occurred while fetching box office data: {e}")
        return []

def save_to_database(data):
    try:
        # 데이터베이스 연결
        conn = mysql.connector.connect(
            host='your_host',
            port=your_port,
            user='your_user',
            password='your_PW',
            database='your_DB'
        )
        cursor = conn.cursor()

        # temp_movie 테이블에 데이터 삽입
        cursor.execute("DELETE FROM temp_movie")
        for movie in data:
            cursor.execute('''
                INSERT INTO temp_movie (
                    movie_num, movie_title, movie_character, movie_genre, movie_Date, movie_running, movie_age, ranking
                ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
            ''', (
                movie['movieCd'], movie['movieNm'], movie['directors'] + ', ' + movie['actors'], movie['genres'], 
                movie['openDt'], movie['showTm'], movie['watchGradeNm'], movie['rank']
            ))

        # 중복되는 순위를 가진 기존 데이터 삭제
        cursor.execute('''
            DELETE m1 FROM movie m1
            INNER JOIN (
                SELECT ranking
                FROM movie
                GROUP BY ranking
                HAVING COUNT(*) > 1
            ) m2 ON m1.ranking = m2.ranking
        ''')

        # 기존 movie 테이블에서 temp_movie와 같은 영화 코드가 있는 경우 순위 업데이트
        cursor.execute('''
            UPDATE movie m
            JOIN temp_movie tm ON m.movie_num = tm.movie_num
            SET m.ranking = tm.ranking
        ''')

        # 기존 movie 테이블에서 temp_movie와 같은 순위를 가진 다른 영화 코드가 있는 경우 삭제
        cursor.execute('''
            DELETE m1 FROM movie m1
            INNER JOIN (
                SELECT m.movie_num
                FROM movie m
                JOIN temp_movie tm ON m.ranking = tm.ranking
                WHERE m.movie_num != tm.movie_num
            ) m2 ON m1.movie_num = m2.movie_num
        ''')

        # temp_movie 데이터를 movie 테이블에 삽입, 기존 영화 정보는 그대로 유지
        cursor.execute('''
            INSERT INTO movie (
                movie_num, movie_title, movie_character, movie_genre, movie_Date, movie_running, movie_age, ranking
            )
            SELECT 
                tm.movie_num, tm.movie_title, tm.movie_character, tm.movie_genre, tm.movie_Date, tm.movie_running, tm.movie_age, tm.ranking
            FROM temp_movie tm
            ON DUPLICATE KEY UPDATE
                ranking = VALUES(ranking)
        ''')

        # 10위 이후의 영화 삭제
        cursor.execute('DELETE FROM movie WHERE ranking > 10')

        conn.commit()
    except mysql.connector.Error as e:
        print(f"Error: {e}")
    finally:
        cursor.close()
        conn.close()



def update_data_periodically():
    while True:
        boxoffice_data = fetch_boxoffice_data()
        if boxoffice_data:
            save_to_database(boxoffice_data)
        time.sleep(43200)  # 12시간마다 데이터 업데이트

if __name__ == '__main__':
    update_data_periodically()


</code></pre>

기존 example에서 달라진 점은 ranking 값을 추가 하였고, ranking의 중복처리를 추가함.
볼드체 된 부분과 open api에서 받아온 키값만 제대로 입력하면 된다.

## step 4) BLOB에 해당 이미지 파일 넣기
자신의 로컬에 있는 이미지 파일을 db의 blob에 등록하는 방법.
example)regist blob_file_to_db를 다운 받아라.


<pre><code>
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


</code></pre>

## php 구현
php_to_DB folder에 있는 파일을 다운 받아서 바로 구현이 가능하다.
ctrl + f 를 눌러 conn 부분에 자신의 DB 정보를 기입하면 끝.


ex)![image](https://github.com/savom/php-db-python/assets/56549021/635df41c-7a35-4110-b5ad-0141332a6f50)

